<?php

namespace Scrutinizer\ResourceManager;

abstract class DelegatingResourceManager implements ResourceManager
{
    private $delegate;

    public function __construct(ResourceManager $rm)
    {
        $this->delegate = $rm;
    }

    public function manage($value)
    {
        $this->delegate->manage($value);
    }

    public function managed(callable $block)
    {
        return $this->delegate->managed($block);
    }
}