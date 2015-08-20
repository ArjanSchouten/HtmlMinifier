<?php

use ArjanSchouten\HTMLMin\Minify;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\Options;

class MinifyTest extends PHPUnit_Framework_TestCase
{
    public function testMinifyAll()
    {
        $context = new MinifyContext(new PlaceholderContainer());
        $context->setContents('<html>'.PHP_EOL.'<p id="test">test</p></html>');
        $minify = new Minify();

        $context = $minify->run($context, [
            Options::ALL => true
        ]);
        $this->assertContains('id=test', $context->getContents());
        $this->assertNotContains(PHP_EOL, $context->getContents());
    }

    public function testMinifySome()
    {
        $context = new MinifyContext(new PlaceholderContainer());
        $context->setContents('<html>'.PHP_EOL.'<p id="test" class="">test</p></html>');
        $minify = new Minify();

        $context = $minify->run($context, [
            Options::EMPTY_ATTRIBUTES => true
        ]);
        $this->assertNotContains('id=test', $context->getContents());
        $this->assertNotContains(PHP_EOL, $context->getContents());
        $this->assertNotContains('class=""', $context->getContents());
    }
}
