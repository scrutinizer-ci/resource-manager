<?php

namespace Scrutinizer\ResourceManager;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Scrutinizer\ResourceManager\Conversion\SymfonyProcessConversion;

class ScopedResourceManager implements ResourceManager
{
    /** @var ImplicitConversion[] */
    private $conversions = array();

    /** @var Scope[] */
    private $scopes = array();

    /** @var Scope */
    private $currentScope;

    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->conversions = array(
            new SymfonyProcessConversion(),
        );

        $this->enterScope();
    }

    /**
     * @param mixed $value A resource, or anything that can be implicitly converted to one.
     */
    public function manage($value)
    {
        if ($value instanceof Resource) {
            $this->currentScope->register($value);

            return;
        }

        foreach ($this->conversions as $conversion) {
            $resource = $conversion->convertMaybe($value);
            if ($resource === null) {
                continue;
            }

            if ( ! $resource instanceof Resource) {
                throw new \RuntimeException(sprintf(
                    'The conversion "%s" did not return an instance of Resource, but %s instead.',
                    get_class($conversion),
                    is_object($resource) ? get_class($resource) : gettype($resource)
                ));
            }

            $this->currentScope->register($resource);

            return;
        }

        throw new \RuntimeException('There was no implicit conversion for the given value.');
    }

    public function managed(callable $block)
    {
        $this->enterScope();

        try {
            $rs = $block();
            $this->leaveScope();

            return $rs;
        } catch (\Exception $ex) {
            $this->leaveScope();

            throw $ex;
        }
    }

    private function leaveScope()
    {
        array_pop($this->scopes)->cleanUp();
        $this->currentScope = end($this->scopes);
    }

    private function enterScope()
    {
        $this->scopes[] = $this->currentScope = new Scope($this->logger);
    }
}