<?php

namespace Scrutinizer\ResourceManager\Conversion;

use Scrutinizer\ResourceManager\ImplicitConversion;
use Scrutinizer\ResourceManager\Resource\SymfonyProcessResource;
use Symfony\Component\Process\Process;

class SymfonyProcessConversion implements ImplicitConversion
{
    public function convertMaybe($value)
    {
        if ( ! $value instanceof Process) {
            return null;
        }

        return new SymfonyProcessResource($value);
    }
}