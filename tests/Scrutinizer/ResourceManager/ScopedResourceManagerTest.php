<?php

namespace Scrutinizer\ResourceManager;

use PHPUnit\Framework\TestCase;
use Scrutinizer\ResourceManager\Resource\TemporaryFile;
use Symfony\Component\Process\Process;

class ScopedResourceManagerTest extends TestCase
{
    /** @var ScopedResourceManager */
    private $rm;

    public function testImplicitlyConvertsProcess()
    {
        $proc = new Process('sleep 60');
        $proc->start();

        $this->rm->managed(function() use ($proc) {
            $this->assertTrue($proc->isRunning());
            $this->rm->manage($proc);
            $this->assertTrue($proc->isRunning());
        });

        $this->assertFalse($proc->isRunning());
    }

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
        $this->assertFileDoesNotExist($tmpFile->getFilename());
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
            $this->assertFileDoesNotExist($tmpFile->getFilename());
        }
    }

    protected function setUp(): void
    {
        $this->rm = new ScopedResourceManager();
    }
}
