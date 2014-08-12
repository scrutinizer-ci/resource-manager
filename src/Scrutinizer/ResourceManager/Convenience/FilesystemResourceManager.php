<?php

namespace Scrutinizer\ResourceManager\Convenience;

use Scrutinizer\ResourceManager\Resource\TemporaryDirectory;
use Scrutinizer\ResourceManager\Resource\TemporaryFile;

trait FilesystemResourceManager
{
    /**
     * A convenience method for creating a managed temporary file.
     *
     * @param string|null $content the temporary file's content
     * @return string The path to the temporary file.
     */
    public function createTemporaryFile($content = null)
    {
        $resource = new TemporaryFile(null, $content);
        $this->manage($resource);

        return $resource->getFilename();
    }

    /**
     * A convenience method for creating a managed temporary directory.
     *
     * @param bool $create
     * @return string
     */
    public function createTemporaryDirectory($create = true)
    {
        $resource = new TemporaryDirectory(null, $create);
        $this->manage($resource);

        return $resource->getDir();
    }


    /**
     * @param mixed $value A resource.
     * @return void
     */
    abstract public function manage($value);
}