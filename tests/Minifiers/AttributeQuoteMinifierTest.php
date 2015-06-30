<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;

class AttributeQuoteMinifierTest extends PHPUnit_Framework_TestCase
{

    private $attributeQuote;

    public function __construct()
    {
        $this->attributeQuote = new AttributeQuoteMinifier();
    }

    public function testAttributeQuoteMinifier()
    {
        $result = $this->attributeQuote->minify('<div id="test"></div>');
        $this->assertEquals('<div id=test></div>', $result);

        $result = $this->attributeQuote->minify('<div id="test>test"></div>');
        $this->assertEquals('<div id="test>test"></div>', $result);

        $result = $this->attributeQuote->minify('<div id="test<test"></div>');
        $this->assertEquals('<div id="test<test"></div>', $result);

        $result = $this->attributeQuote->minify("<div id=\"test" . PHP_EOL . "test\"></div>");
        $this->assertEquals("<div id=\"test" . PHP_EOL . "test\"></div>", $result);

        $result = $this->attributeQuote->minify('<div id="test`test"></div>');
        $this->assertEquals('<div id="test`test"></div>', $result);

        $result = $this->attributeQuote->minify('<div id="test&"></div>');
        $this->assertEquals('<div id="test&"></div>', $result);

        $result = $this->attributeQuote->minify('<div id="test=test"></div>');
        $this->assertEquals('<div id="test=test"></div>', $result);

        $result = $this->attributeQuote->minify('<div id="\'test"></div>');
        $this->assertEquals('<div id="\'test"></div>', $result);

        $result = $this->attributeQuote->minify('<div style="float:right;"></div>');
        $this->assertEquals('<div style=float:right;></div>', $result);
    }

    public function testEscapedQuotes()
    {
        //test if the unrolling the loop technique is implemented correctly
        $result = $this->attributeQuote->minify('<div onclick="alert(\"test\")"></div>');
        $this->assertEquals('<div onclick="alert(\"test\")"></div>', $result);

        $result = $this->attributeQuote->minify('<div onclick="alert(\'test\')"></div>');
        $this->assertEquals('<div onclick="alert(\'test\')"></div>', $result);

        $result = $this->attributeQuote->minify('<div onclick=\'alert(\\\'test\\\')\'></div>');
        $this->assertEquals('<div onclick=\'alert(\\\'test\\\')\'></div>', $result);
    }

    public function testWhitespaces()
    {
        $result = $this->attributeQuote->minify('<div style ="float:right;"></div>');
        $this->assertEquals('<div style =float:right;></div>', $result);

        $result = $this->attributeQuote->minify('<div style= "float:right;"></div>');
        $this->assertEquals('<div style=float:right;></div>', $result);

        $result = $this->attributeQuote->minify('<div style=' . PHP_EOL . '"float:right;"></div>');
        $this->assertEquals('<div style=float:right;></div>', $result);
    }
}