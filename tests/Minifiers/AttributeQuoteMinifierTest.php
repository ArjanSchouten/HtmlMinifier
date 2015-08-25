<?php

use ArjanSchouten\HtmlMinifier\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;

class AttributeQuoteMinifierTest extends PHPUnit_Framework_TestCase
{
    private $attributeQuote;

    public function setUp()
    {
        $this->attributeQuote = new AttributeQuoteMinifier();
    }

    public function testAttributeQuoteMinifier()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->attributeQuote->process($context->setContents('<div id="test"></div>'));
        $this->assertEquals('<div id=test></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test>test"></div>'));
        $this->assertEquals('<div id="test>test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test<test"></div>'));
        $this->assertEquals('<div id="test<test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test test"></div>'));
        $this->assertEquals('<div id="test test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test'.PHP_EOL.'test"></div>'));
        $this->assertEquals('<div id="test'.PHP_EOL.'test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test`test"></div>'));
        $this->assertEquals('<div id="test`test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test&"></div>'));
        $this->assertEquals('<div id="test&"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="test=test"></div>'));
        $this->assertEquals('<div id="test=test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div id="\'test"></div>'));
        $this->assertEquals('<div id="\'test"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div style="float:right;"></div>'));
        $this->assertEquals('<div style=float:right;></div>', $result->getContents());
    }

    public function testEscapedQuotes()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        //test if the unrolling the loop technique is implemented correctly
        $result = $this->attributeQuote->process($context->setContents('<div onclick="alert(\"test\")"></div>'));
        $this->assertEquals('<div onclick="alert(\"test\")"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div onclick="alert(\"test\")"></div>'.PHP_EOL.'<div id="test"></div>'));
        $this->assertEquals('<div onclick="alert(\"test\")"></div>'.PHP_EOL.'<div id=test></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div onclick="alert(\'test\')"></div>'));
        $this->assertEquals('<div onclick="alert(\'test\')"></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div onclick=\'alert(\\\'test\\\')\'></div>'));
        $this->assertEquals('<div onclick=\'alert(\\\'test\\\')\'></div>', $result->getContents());
    }

    public function testWhitespaces()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->attributeQuote->process($context->setContents('<div style ="float:right;"></div>'));
        $this->assertEquals('<div style =float:right;></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div style= "float:right;"></div>'));
        $this->assertEquals('<div style=float:right;></div>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<div style='.PHP_EOL.'"float:right;"></div>'));
        $this->assertEquals('<div style=float:right;></div>', $result->getContents());
    }

    public function testPlaceholders()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->attributeQuote->process($context->setContents('<a href="[[aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa1]]"></a>'));
        $this->assertEquals('<a href="[[aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa1]]"></a>', $result->getContents());

        $result = $this->attributeQuote->process($context->setContents('<a href="[[aaaaaaaaaaaaaaaaaa1]]"></a>'));
        $this->assertEquals('<a href=[[aaaaaaaaaaaaaaaaaa1]]></a>', $result->getContents());
    }
}
