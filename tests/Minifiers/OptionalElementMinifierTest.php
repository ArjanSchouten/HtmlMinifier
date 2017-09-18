<?php

use ArjanSchouten\HtmlMinifier\Minifiers\Html\OptionalElementMinifier;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;

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

    public function testDtDdTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<dt>foo</dt><dd>bar</dd>'));
        $this->assertEquals('<dt>foo<dd>bar</dd>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<dl><dt>foo</dt><dd>bar</dd></dl>'));
        $this->assertEquals('<dl><dt>foo<dd>bar</dl>', $result->getContents());
    }

    public function testPTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<p>test</p>'));
        $this->assertEquals('<p>test</p>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<p>test</p><h1>test</h1>'));
        $this->assertEquals('<p>test<h1>test</h1>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<a><p>test</p></a>'));
        $this->assertEquals('<a><p>test</p></a>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<div><p>test</p></div>'));
        $this->assertEquals('<div><p>test</div>', $result->getContents());
    }

    public function testColgroupTag()
    {
        $context = new MinifyContext(new PlaceholderContainer());

        $result = $this->minifier->process($context->setContents('<colgroup><col></colgroup>'));
        $this->assertEquals('<colgroup><col>', $result->getContents());

        $result = $this->minifier->process($context->setContents('<colgroup><col></colgroup><!---->'));
        $this->assertEquals('<colgroup><col></colgroup><!---->', $result->getContents());
    }
}
