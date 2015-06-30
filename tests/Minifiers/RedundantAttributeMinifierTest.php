<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;

class RedundantAttributeMinifierTest extends PHPUnit_Framework_TestCase
{

    private $redundantAttributeFileMinifier;

    public function __construct()
    {
        $this->redundantAttributeFileMinifier = new RedundantAttributeMinifier();
    }

    public function testAttributeRemoval()
    {
        $result = $this->redundantAttributeFileMinifier->minify('<script language="javascript"></script>');
        $this->assertEquals('<script></script>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<script language=\'javascript\'></script>');
        $this->assertEquals('<script></script>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<script id="my-id" language=\'javascript\'></script>');
        $this->assertEquals('<script id="my-id"></script>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<script id="my-id" language=' . PHP_EOL . '\'javascript\'></script>');
        $this->assertEquals('<script id="my-id"></script>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<script id="test" language=\'javascript\' type="text/javascript" class="test"></script>');
        $this->assertEquals('<script id="test" class="test"></script>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<form method=get></form>');
        $this->assertEquals('<form></form>', $result);

        $result = $this->redundantAttributeFileMinifier->minify('<form method=get action="test"></form>');
        $this->assertEquals('<form action="test"></form>', $result);
    }
}