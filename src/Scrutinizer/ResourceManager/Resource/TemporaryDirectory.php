<?php

namespace Scrutinizer\ResourceManager\Resource;

use Scrutinizer\ResourceManager\Resource;

class TemporaryDirectory implements Resource
{
    private $dir;

    public function __construct($dir = null, $create = false)
    {
        $this->dir = $dir ?: tempnam(sys_get_temp_dir(), 'scrutinizer-rm');

        if (is_file($this->dir)) {
            unlink($this->dir);
        }

        if ($create && ! is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function destroy()
    {
        $this->destroyRecursive($this->dir);
    }

    private function destroyRecursive($dir)
    {
        $h = opendir($dir);
        while ($name = readdir($h)) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $path = $dir.'/'.$name;

            if (is_dir($path)) {
                $this->destroyRecursive($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }

        closedir($h);
    }
}