<?php

namespace Scrutinizer\ResourceManager;

interface ImplicitConversion
{
    /**
     * @param mixed $value
     * @return Option for an instance of Resource (Option<Resource>)
     */
    public function convertMaybe($value);
}