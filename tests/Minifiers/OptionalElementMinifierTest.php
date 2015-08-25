<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\OptionalElementMinifier;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class OptionalElementMinifierTest extends PHPUnit_Framework_TestCase
{
    private $minifier;

    public function setUp()
    {
        $this->minifier = new OptionalElementMinifier();
    }

    public function testHtmlTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<html><head></head><body><p></p></body></html>'));
        $this->assertEquals('<p></p>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<html><!--test--><head></head><body></body></html>'));
        $this->assertContains('<html>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<html lang="en"><head></head><body></body></html>'));
        $this->assertContains('<html lang="en">', $result->getContents());

        $result = $this->minifier->process($context->setContents('<html lang="en"><head></head><body></body></html><!--test-->'));
        $this->assertEquals('<html lang="en"></html><!--test-->', $result->getContents());
    }

    public function testHeadTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<html><head><link rel=""></head> <body></body></html>'));
        $this->assertEquals('<link rel=""></head> ', $result->getContents());

        $result = $this->minifier->process($context->setContents('<html><head><link rel=""></head><!----><body></body></html>'));
        $this->assertEquals('<link rel=""></head><!---->', $result->getContents());
    }

    public function testBodyTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<body></body>'));
        $this->assertEquals('', $result->getContents());

        $result = $this->minifier->process($context->setContents('<body> </body>'));
        $this->assertEquals('<body> ', $result->getContents());

        $result = $this->minifier->process($context->setContents('<body><!----></body>'));
        $this->assertEquals('<body><!---->', $result->getContents());

        $result = $this->minifier->process($context->setContents('<body><script></script></body><!---->'));
        $this->assertEquals('<body><script></script></body><!---->', $result->getContents());

        $result = $this->minifier->process($context->setContents('<body><a href="http://www.example.com"></a></body>'));
        $this->assertEquals('<a href="http://www.example.com"></a>', $result->getContents());
    }

    public function testLiTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<ul><li>foo</li><li>bar</li></ul>'));
        $this->assertEquals('<ul><li>foo<li>bar</ul>', $result->getContents());
    }
}