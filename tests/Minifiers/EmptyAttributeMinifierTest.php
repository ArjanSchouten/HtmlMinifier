<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\HtmlBooleanAttributeRepository;
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
        $result = $this->emptyAttributeMinifier->minify('<div id=""></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div rel="" class="btn"></div>');
        $this->assertEquals('<div class="btn"></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id="test" rel="" class="btn"></div>');
        $this->assertEquals('<div id="test" class="btn"></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id="" rel="" class="btn"></div>');
        $this->assertEquals('<div class="btn"></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id="" rel="" class="btn"></div>');
        $this->assertEquals('<div class="btn"></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id="" rel="" class="btn"></div>');
        $this->assertEquals('<div class="btn"></div>', $result);
    }

    public function testDataAttributes()
    {
        $result = $this->emptyAttributeMinifier->minify('<div data-my-data-attribute></div>');
        $this->assertEquals('<div data-my-data-attribute></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div data-my-data-attribute=""></div>');
        $this->assertEquals('<div data-my-data-attribute></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div data-my-data-attribute="test"></div>');
        $this->assertEquals('<div data-my-data-attribute="test"></div>', $result);
    }

    public function testBooleanAttributes()
    {
        $repository = m::mock(HtmlBooleanAttributeRepository::class)->shouldReceive('getAttributes')->andReturn(
            new Collection(['async', 'defer'])
        )->getMock();

        $this->emptyAttributeMinifier->setRepository($repository);

        $result = $this->emptyAttributeMinifier->minify('<script defer></script>');
        $this->assertEquals('<script defer></script>', $result);

        $result = $this->emptyAttributeMinifier->minify('<script defer=""></script>');
        $this->assertEquals('<script defer></script>', $result);

        $result = $this->emptyAttributeMinifier->minify('<script id="test" defer=""></script>');
        $this->assertEquals('<script id="test" defer></script>', $result);

        $result = $this->emptyAttributeMinifier->minify('<script defer="" id="test"></script>');
        $this->assertEquals('<script defer id="test"></script>', $result);
    }

    public function testWhitespaces()
    {
        $result = $this->emptyAttributeMinifier->minify('<div id =""></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id= ""></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id=' . PHP_EOL . '""></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id' . PHP_EOL . '=""></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->emptyAttributeMinifier->minify('<div id="' . PHP_EOL . '"></div>');
        $this->assertEquals('<div></div>', $result);
    }
}