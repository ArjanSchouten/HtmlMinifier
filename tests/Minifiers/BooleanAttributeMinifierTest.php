<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\BooleanAttributeMinifier;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class BooleanAttributeMinifierTest extends PHPUnit_Framework_TestCase
{
    private $booleanAttribute;

    public function setUp()
    {
        $this->booleanAttribute = new BooleanAttributeMinifier();
    }

    public function testBooleanAttribute()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->booleanAttribute->process($context->setContents('<input checked=checked/>'));
        $this->assertEquals('<input checked/>', $result->getContents());

        $result = $this->booleanAttribute->process($context->setContents('<input class=class/>'));
        $this->assertEquals('<input class=class/>', $result->getContents());

        $result = $this->booleanAttribute->process($context->setContents('<input checked=true/>'));
        $this->assertEquals('<input checked/>', $result->getContents());

        $result = $this->booleanAttribute->process($context->setContents('<script async=false id="true"></script>'));
        $this->assertEquals('<script id="true"></script>', $result->getContents());

        $result = $this->booleanAttribute->process($context->setContents('<script async="false" checked='.PHP_EOL.'\'checked\' id="true"></script>'));
        $this->assertEquals('<script checked id="true"></script>', $result->getContents());

        $result = $this->booleanAttribute->process($context->setContents('<script async="" checked='.PHP_EOL.'\'\' id="id"></script>'));
        $this->assertEquals('<script async checked id="id"></script>', $result->getContents());
    }
}