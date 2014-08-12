# Robust, Automatic Resource Management

This library helps you manage resources with an easy-to-use interface. Resources like temporary file, or directories
are automatically clean-up when leaving the scope, background processes are stopped, and any other custom resources
are destroyed.

## Usage

```php
use Scrutinizer\ResourceManager\DefaultResourceManager;


class SomeClass {
    private $rm;

    public function __construct(ResourceManager $rm)
    {
        $this->rm = new FilesystemResourceManager($rm);
    }

    public function run()
    {
        $rs = $this->rm->managed(function() {
            $someTmpFile = $this->rm->createTemporaryFile();

            // do something with $someTmpFile

            return $result;
        });

        // $someTmpFile has been automatically cleaned-up here.
    }
}

$someClass = new SomeClass(new ScopedResourceManager());
$someClass->run();
```

## Resource Manager Implementations

By default, you would want to use the ``ScopedResourceManager`` which allows arbitrary nesting of scopes.
This manager can also be wrapped by other implementations which provide some convenience methods like f.e.
the ``FilesystemResourceManager`` which allows you to easily create managed temporary files, or directories.

## Background Processes

If you start a background process, like a server f.e., that you want to clean-up when leaving the scope,
you can use the resource manager's ``manage`` method:

```php
use Scrutinizer\ResourceManager\ScopedResourceManager;
use Symfony\Component\Process\Process;

class SomeTest extends \PHPUnit_Framework_TestCase
{
    private $rm;
    
    public function testSomething()
    {
        // The resource manager's global scope will automatically be cleaned up 
        // when PHP calls all object destructors.
    }
    
    protected function setUp()
    {
        $this->rm = new ScopedResourceManager();
        
        $proc = new Process('some-server-cmd');
        $proc->start();
        $this->rm->manage($proc);
    }
}
```


## Custom Resources

By default, this library supports temporary files, temporary directories, and Symfony background processes (using its Process component). You can also add create your own resources by implementing ``Scrutinizer\ResourceManager\Resource``:

```php
use Scrutinizer\ResourceManager\Resource;
use Scrutinizer\ResourceManager\ScopedResourceManager;

class PhpFileResource implements Resource 
{
    private $handle;

    public function __construct($fileHandle)
    {
        $this->handle = $fileHandle;
    }

    public function destroy()
    {
        fclose($this->handle);
    }
}

$rm = new ScopedResourceManager();
$rm->manage(new PhpFileResource($someHandle));
```
