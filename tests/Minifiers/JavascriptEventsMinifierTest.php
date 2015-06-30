<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;

class JavascriptEventsMinifierTest extends PHPUnit_Framework_TestCase
{
    private $javascriptEventsMinifier;

    public function __construct()
    {
        $this->javascriptEventsMinifier = new JavascriptEventsMinifier();
    }

    public function testMinify()
    {
        $result = $this->javascriptEventsMinifier->minify(');"></button>');
        $this->assertEquals('<button onclick="alert(\'test\');"></button>', $result);

        $result = $this->javascriptEventsMinifier->minify('<button click="javascript:alert(\'test\');"></button>');
        $this->assertEquals('<button click="javascript:alert(\'test\');"></button>', $result);
    }
}