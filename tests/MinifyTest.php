<?php

use ArjanSchouten\HtmlMinifier\Minifiers\Html\OptionalElementMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\Minify;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Options;
use ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface;
use Illuminate\Support\Arr;

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

    public function testAddPlaceholder()
    {
        $minify = new Minify();

        $oldPlaceholders = $minify->getPlaceholders();
        $oldMinifiers = $minify->getMinifiers();

        try {
            $minify->addPlaceholder(Stub::class);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
        }

        try {
            $minify->addPlaceholder(OptionalElementMinifier::class);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
        }

        $minify->addPlaceholder(StubPlaceholderWithInterface::class);
        $this->assertTrue(in_array(StubPlaceholderWithInterface::class, $minify->getPlaceholders()));

        $minify->addMinifier(StubMinifierWithInterface::class);
        $this->assertTrue(in_array(StubMinifierWithInterface::class, $minify->getMinifiers()));
    }

    public function testMinifyMeasurement()
    {
        $context = new MinifyContext(new PlaceholderContainer());
        $context->setContents('<html>'.PHP_EOL.'<p id="test" class="">test</p></html>');
        $minify = new Minify();

        $context = $minify->run($context, [
            Options::EMPTY_ATTRIBUTES => true
        ]);

        $measurement = $context->getStatistics();
        $this->assertEquals(5, count($measurement->getReferencePoints()));

        $callable = function() { return true; };
        $this->assertLessThan(Arr::first($measurement->getReferencePoints(), $callable), Arr::last($measurement->getReferencePoints(), $callable));
    }
}

class Stub{}
class StubPlaceholderWithInterface implements PlaceholderInterface {
    public function process($payload)
    {
    }
}
class StubMinifierWithInterface implements MinifierInterface {
    public function process(MinifyContext $context)
    {
    }
    public function provides()
    {
    }
}
