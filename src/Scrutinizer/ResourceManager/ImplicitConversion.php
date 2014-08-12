<?php

namespace Scrutinizer\ResourceManager;

interface ImplicitConversion
{
    /**
     * @param mixed $value
     * @return Resource|null
     */
    public function convertMaybe($value);
}