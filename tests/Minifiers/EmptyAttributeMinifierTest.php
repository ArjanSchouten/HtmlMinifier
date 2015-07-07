<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\HtmlBooleanAttributeRepository;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use Illuminate\Support\Collection;
use Mockery as m;

class EmptyAttribute extends PHPUnit_Framework_TestCase
{

    private $emptyAttributeMinifier;

    public function __construct()
    {
        $this->emptyAttributeMinifier = new EmptyAttributeMinifier();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testEmptyAttributes()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id=""></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div rel="" class="btn"></div>'));
        $this->assertEquals('<div class="btn"></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id="test" rel="" class="btn"></div>'));
        $this->assertEquals('<div id="test" class="btn"></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id="" rel="" class="btn"></div>'));
        $this->assertEquals('<div class="btn"></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id="" rel="" class="btn"></div>'));
        $this->assertEquals('<div class="btn"></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id="" rel="" class="btn"></div>'));
        $this->assertEquals('<div class="btn"></div>', $result->getContents());
    }

    public function testDataAttributes()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div data-my-data-attribute></div>'));
        $this->assertEquals('<div data-my-data-attribute></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div data-my-data-attribute=""></div>'));
        $this->assertEquals('<div data-my-data-attribute></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div data-my-data-attribute="test"></div>'));
        $this->assertEquals('<div data-my-data-attribute="test"></div>', $result->getContents());
    }

    public function testBooleanAttributes()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $repository = m::mock(HtmlBooleanAttributeRepository::class)->shouldReceive('getAttributes')->andReturn(
            new Collection(['async', 'defer'])
        )->getMock();

        $this->emptyAttributeMinifier->setRepository($repository);

        $result = $this->emptyAttributeMinifier->process($context->setContents('<script defer></script>'));
        $this->assertEquals('<script defer></script>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<script defer=""></script>'));
        $this->assertEquals('<script defer></script>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<script id="test" defer=""></script>'));
        $this->assertEquals('<script id="test" defer></script>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<script defer="" id="test"></script>'));
        $this->assertEquals('<script defer id="test"></script>', $result->getContents());
    }

    public function testWhitespaces()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id =""></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id= ""></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id=' . PHP_EOL . '""></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id' . PHP_EOL . '=""></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->emptyAttributeMinifier->process($context->setContents('<div id="' . PHP_EOL . '"></div>'));
        $this->assertEquals('<div></div>', $result->getContents());
    }
}