<?php

namespace Scrutinizer\ResourceManager;

interface ResourceManager
{
    /**
     * @param mixed $value A resource, or anything that can be converted to one by the underlying manager.
     * @return void
     */
    public function manage($value);

    /**
     * @param callable $block
     * @return void
     */
    public function managed(callable $block);
}