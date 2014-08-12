<?php

namespace Scrutinizer\ResourceManager;

use Scrutinizer\ResourceManager\Convenience\FilesystemResourceManager;

class FilesystemManager extends DelegatingResourceManager
{
    use FilesystemResourceManager;
}