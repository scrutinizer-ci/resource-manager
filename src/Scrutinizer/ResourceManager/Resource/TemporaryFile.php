<?php

namespace Scrutinizer\ResourceManager\Resource;

use Scrutinizer\ResourceManager\Resource;

class TemporaryFile implements Resource
{
    private $filename;

    public function __construct($filename = null)
    {
        $this->filename = $filename ?: tempnam(sys_get_temp_dir(), 'scrutinizer-rm');
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function destroy()
    {
        @unlink($this->filename);
    }
}