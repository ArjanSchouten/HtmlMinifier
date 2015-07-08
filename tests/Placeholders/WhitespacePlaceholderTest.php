<?php

use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\Placeholders\WhitespacePlaceholder;
use Mockery as m;

class WhitespacePlaceholderText extends PHPUnit_Framework_TestCase
{
    private $whitespacePlaceholderText;

    public function __construct()
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
        $context = new MinifyPipelineContext($placeholderContainer);

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
    }
}
