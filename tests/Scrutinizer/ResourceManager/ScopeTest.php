<?php

namespace Scrutinizer\ResourceManager;

class ScopeTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilesystemResourceManager */
    private $rm;

    public function testResourcesAreOnlyCleanedUpOnce()
    {
        $scriptFile = $this->rm->createTemporaryFile(sprintf(<<<'CODE'
<?php

require %s;

use Scrutinizer\ResourceManager\Resource;

class MyResource implements Resource
{
    public function destroy()
    {
        echo "Called\n";
    }
}

$rm = new Scrutinizer\ResourceManager\ScopedResourceManager();
$rm->managed(function() use ($rm) {
    $rm->manage(new MyResource());
});

CODE
            ,
            var_export(__DIR__.'/../../../vendor/autoload.php', true)
        ));

        exec('php '.$scriptFile, $output, $returnVar);

        $this->assertEquals(0, $returnVar);
        $this->assertEquals("Called", implode('', $output));
    }

    protected function setUp()
    {
        $this->rm = new FilesystemResourceManager(new ScopedResourceManager());
    }
}