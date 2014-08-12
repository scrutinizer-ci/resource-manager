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

            // $someTmpFile is automatically clean-up here.
        }
    }

    $someClass = new SomeClass(new ScopedResourceManager());
    $someClass->run();

```
