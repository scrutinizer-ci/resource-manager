<?php

namespace Scrutinizer\ResourceManager\Resource;

use Scrutinizer\ResourceManager\Resource;

class TemporaryFile implements Resource
{
    private $filename;

    public function __construct($filename = null, $content = null)
    {
        $this->filename = $filename ?: tempnam(sys_get_temp_dir(), 'scrutinizer-rm');

        if ($content !== null) {
            file_put_contents($this->filename, $content);
        }
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