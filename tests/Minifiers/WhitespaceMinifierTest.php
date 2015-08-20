<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class WhitespaceMinifierTest extends PHPUnit_Framework_TestCase
{
    private $whitespaceMinifier;

    public function setUp()
    {
        $this->whitespaceMinifier = new WhitespaceMinifier();
    }

    public function testTrailingWhitespaces()
    {
        $result = $this->whitespaceMinifier->trailingWhitespaces('  Lorum Ipsum  ');
        $this->assertEquals('Lorum Ipsum', $result);
    }

    public function testEqualSignRule()
    {
        $expected = '<a href="http://www.example.com">Lorum Ipsum</a>';
        $result = $this->whitespaceMinifier->runMinificationRules('<a href = "http://www.example.com">Lorum Ipsum</a>');
        $this->assertEquals($expected, $result);

        $result = $this->whitespaceMinifier->runMinificationRules('<a href ="http://www.example.com">Lorum Ipsum</a>');
        $this->assertEquals($expected, $result);

        $result = $this->whitespaceMinifier->runMinificationRules('<a href= \'http://www.example.com\'>Lorum Ipsum</a>');
        $this->assertEquals('<a href=\'http://www.example.com\'>Lorum Ipsum</a>', $result);

        $result = $this->whitespaceMinifier->runMinificationRules('<a href = http://www.example.com >Lorum Ipsum</a>');
        $this->assertEquals('<a href=http://www.example.com>Lorum Ipsum</a>', $result);
    }

    public function testWhitespaces()
    {
        $result = $this->whitespaceMinifier->runMinificationRules("<a href=//www.example.nl>\n\r\t</a>");
        $this->assertEquals('<a href=//www.example.nl></a>', $result);
    }

    public function testHtmlSelfClosingTags()
    {
        $expected = '<link rel=stylesheet type="text/css" href="">';

        $result = $this->whitespaceMinifier->runMinificationRules('<link rel=stylesheet type="text/css" href=""/>');
        $this->assertEquals($expected, $result);

        $result = $this->whitespaceMinifier->runMinificationRules('<link rel=stylesheet type="text/css" href="" />');
        $this->assertEquals($expected, $result);
    }

    public function testSpaceBetweenTags()
    {
        $result = $this->whitespaceMinifier->runMinificationRules('<p> </p>');
        $this->assertEquals('<p></p>', $result);
    }

    public function testSpacesAroundPlaceholders()
    {
        $placeholderContainer = new PlaceholderContainer();
        $placeholder = $placeholderContainer->addPlaceholder(null);
        $expected = '<a href="'.$placeholder.'"></a>';

        $result = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href=" '.$placeholder.'"></a>');
        $this->assertEquals($expected, $result);

        $result = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href="'.$placeholder.' "></a>');
        $this->assertEquals($expected, $result);

        $result = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href=\' '.$placeholder.' \'></a>');
        $this->assertEquals('<a href=\''.$placeholder.'\'></a>', $result);
    }

    public function testMaxLineLength()
    {
        $maxLineLength = 25;
        $result = $this->whitespaceMinifier->maxHtmlLineLength('This should break at <p></p> This should break at <p></p>',
            $maxLineLength);
        $this->assertEquals('This should break at <p>'.PHP_EOL.'</p> This should break at <p>'.PHP_EOL.'</p>', $result);
    }
}
