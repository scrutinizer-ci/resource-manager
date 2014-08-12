<?php

namespace Scrutinizer\ResourceManager;

use Scrutinizer\ResourceManager\Resource\TemporaryFile;

class ScopedResourceManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ScopedResourceManager */
    private $rm;

    public function testManaged()
    {
        $rs = $this->rm->managed(function() use (&$tmpFile) {
            $tmpFile = new TemporaryFile();
            $this->assertFileExists($tmpFile->getFilename());

            $this->rm->manage($tmpFile);

            $this->assertFileExists($tmpFile->getFilename());

            return 'foo';
        });

        $this->assertEquals('foo', $rs);
        $this->assertFileNotExists($tmpFile->getFilename());
    }

    public function testResourceIsCleanedUpWhenExceptionIsThrown()
    {
        try {
            $this->rm->managed(function() use (&$tmpFile) {
                $tmpFile = new TemporaryFile();
                $this->assertFileExists($tmpFile->getFilename());

                $this->rm->manage($tmpFile);

                throw new \RuntimeException('Foo');
            });
            $this->fail('Exception expected.');
        } catch (\RuntimeException $ex) {
            $this->assertEquals('Foo', $ex->getMessage());
            $this->assertFileNotExists($tmpFile->getFilename());
        }
    }

    protected function setUp()
    {
        $this->rm = new ScopedResourceManager();
    }
}