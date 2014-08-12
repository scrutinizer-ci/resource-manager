<?php

namespace Scrutinizer\ResourceManager\Conversion;

use Scrutinizer\ResourceManager\ImplicitConversion;
use Scrutinizer\ResourceManager\Resource\SymfonyProcessResource;
use Symfony\Component\Process\Process;
use PhpOption\None;
use PhpOption\Some;

class SymfonyProcessConversion implements ImplicitConversion
{
    public function convertMaybe($value)
    {
        if ( ! $value instanceof Process) {
            return None::create();
        }

        return new Some(new SymfonyProcessResource($value));
    }
}