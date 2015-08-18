<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class JavascriptEventsMinifierTest extends PHPUnit_Framework_TestCase
{
    private $javascriptEventsMinifier;

    public function setUp()
    {
        $this->javascriptEventsMinifier = new JavascriptEventsMinifier();
    }

    public function testMinify()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->javascriptEventsMinifier->process($context->setContents('<button onclick="javascript:alert(\'test\');"></button>'));
        $this->assertEquals('<button onclick="alert(\'test\');"></button>', $result->getContents());

        $result = $this->javascriptEventsMinifier->process($context->setContents('<button click="javascript:alert(\'test\');"></button>'));
        $this->assertEquals('<button click="javascript:alert(\'test\');"></button>', $result->getContents());

        $result = $this->javascriptEventsMinifier->process($context->setContents('<button onclick ="javascript:alert(\'test\');"></button>'));
        $this->assertEquals('<button onclick ="alert(\'test\');"></button>', $result->getContents());
    }
}
