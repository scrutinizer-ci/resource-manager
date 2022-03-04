<?php

namespace Scrutinizer\ResourceManager\Resource;

use PHPUnit\Framework\TestCase;

class TemporaryDirectoryTest extends TestCase
{
    public function testDestroy()
    {
        $resource = new TemporaryDirectory(null, true);

        file_put_contents($resource->getDir().'/foo', 'abcdef');
        link($resource->getDir().'/foo', $resource->getDir().'/bar');

        mkdir($resource->getDir().'/foobar/baz', 0777, true);
        file_put_contents($resource->getDir().'/foobar/baz/ffifi', 'dididiiddiid');

        $resource->destroy();

        $this->assertTrue( ! is_dir($resource->getDir()));
    }
}
