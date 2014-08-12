<?php

namespace Scrutinizer\ResourceManager;

use Psr\Log\LoggerInterface;

class Scope
{
    private $logger;

    /** @var Resource[] */
    private $resources = array();

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register(Resource $resource)
    {
        $this->resources[] = $resource;
    }

    public function cleanUp()
    {
        $this->__destruct();
    }

    public function __destruct()
    {
        foreach ($this->resources as $resource) {
            try {
                $resource->destroy();
            } catch (\Exception $ex) {
                try {
                    $this->logger->error('Error while cleaning up resource: {message}', array(
                        'message' => $ex->getMessage(),
                        'resource' => $resource,
                    ));
                } catch (\Exception $ex) {
                    // Nothing, we can do here.
                }
            }
        }

        $this->resources = array();
    }
}