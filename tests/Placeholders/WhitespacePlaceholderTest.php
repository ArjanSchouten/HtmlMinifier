<?php

use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Placeholders\WhitespacePlaceholder;
use Mockery as m;

class WhitespacePlaceholderTest extends PHPUnit_Framework_TestCase
{
    private $whitespacePlaceholderText;

    public function setUp()
    {
        $this->whitespacePlaceholderText = new WhitespacePlaceholder();
    }

    public function tearDown()
    {
        m::close();
    }

    public function test()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyContext($placeholderContainer);

        $result = $this->whitespacePlaceholderText->process($context->setContents('<script id="myid">test</script>'));
        $this->assertEquals('<script id="myid">'.$placeholder.'</script>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<script id="test">test</script>'));
        $this->assertEquals('<script id="test">'.$placeholder.'</script>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<script id="tes>t">test</script>'));
        $this->assertEquals('<script id="tes>t">'.$placeholder.'</script>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<script id=test>test</script>'));
        $this->assertEquals('<script id=test>'.$placeholder.'</script>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<script id=test>te'.PHP_EOL.'st</script>'));
        $this->assertEquals('<script id=test>'.$placeholder.'</script>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents("<script>\\\\test</script><script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'xxxx', 'auto');
ga('send', 'pageview');
</script>"));
        $this->assertEquals('<script>'.$placeholder.'</script><script>'.$placeholder.'</script>', $result->getContents());
    }

    public function testWhitespacesBetweenInlineElements()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyContext($placeholderContainer);

        $result = $this->whitespacePlaceholderText->process($context->setContents('<span></span> <span></span>'));
        $this->assertEquals('<span></span>'.$placeholder.'<span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<button></button> <span></span>'));
        $this->assertEquals('<button></button>'.$placeholder.'<span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<button id="test"></button> <span></span>'));
        $this->assertEquals('<button id="test"></button>'.$placeholder.'<span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<button id="test">bla</button>    <span></span>'));
        $this->assertEquals('<button id="test">bla</button>'.$placeholder.'<span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<button id="test">bla</button>'.PHP_EOL.'<span></span>'));
        $this->assertEquals('<button id="test">bla</button>'.$placeholder.'<span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents('<small></small><span></span>'));
        $this->assertEquals('<small></small><span></span>', $result->getContents());

        $result = $this->whitespacePlaceholderText->process($context->setContents(rtrim(str_repeat('<a><span></span></a>'.PHP_EOL, 4)), PHP_EOL));
        $this->assertEquals(rtrim(str_repeat('<a><span></span></a>'.$placeholder, 4),$placeholder), $result->getContents());
    }
}
