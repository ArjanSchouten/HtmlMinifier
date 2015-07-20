<?php

use Mockery as m;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;

class BladePlaceholderTest extends PHPUnit_Framework_TestCase
{
    private $bladePlaceholder;

    public function setUp()
    {
        $this->bladePlaceholder = new BladePlaceholder();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testBladeEchos()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyPipelineContext($placeholderContainer);

        $tags = [['{{', '}}']];
        BladePlaceholder::setBladeTags($tags);

        $result = $this->bladePlaceholder->process($context->setContents('{{ echo \'test\';}}'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->bladePlaceholder->process($context->setContents('{{ echo \'test'.PHP_EOL.'\';}}'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->bladePlaceholder->process($context->setContents('test{{ echo \'test'.PHP_EOL.'\';}}test'));
        $this->assertEquals('test'.$placeholder.'test', $result->getContents());
        $this->assertEquals('test'.$placeholder.'test', $result->getContents());
    }

    public function testBladeControlStructures()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyPipelineContext($placeholderContainer);

        $tags = [['{{', '}}']];
        BladePlaceholder::setBladeTags($tags);

        $result = $this->bladePlaceholder->process($context->setContents('@if(true)'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->bladePlaceholder->process($context->setContents('@endif'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->bladePlaceholder->process($context->setContents('@if(true == true)'.PHP_EOL.'some html@endif'));
        $this->assertEquals($placeholder.PHP_EOL.'some html'.$placeholder, $result->getContents());
    }
}