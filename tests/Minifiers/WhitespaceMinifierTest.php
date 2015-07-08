<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class WhitespaceMinifierTest extends PHPUnit_Framework_TestCase
{

    private $whitespaceMinifier;

    public function __construct()
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
        $result1 = $this->whitespaceMinifier->runMinificationRules('<a href = "http://www.example.com">Lorum Ipsum</a>');
        $result2 = $this->whitespaceMinifier->runMinificationRules('<a href ="http://www.example.com">Lorum Ipsum</a>');
        $result3 = $this->whitespaceMinifier->runMinificationRules('<a href= \'http://www.example.com\'>Lorum Ipsum</a>');
        $result4 = $this->whitespaceMinifier->runMinificationRules('<a href = http://www.example.com >Lorum Ipsum</a>');
        $expected = '<a href="http://www.example.com">Lorum Ipsum</a>';
        $this->assertEquals($expected, $result1);
        $this->assertEquals($expected, $result2);
        $this->assertEquals('<a href=\'http://www.example.com\'>Lorum Ipsum</a>', $result3);
        $this->assertEquals('<a href=http://www.example.com>Lorum Ipsum</a>', $result4);
    }

    public function testWhitespaces()
    {
        $result = $this->whitespaceMinifier->runMinificationRules("<a href=//www.example.nl>\n\r\t</a>");
        $this->assertEquals('<a href=//www.example.nl></a>', $result);
    }

    public function testHtmlSelfClosingTags()
    {
        $result1 = $this->whitespaceMinifier->runMinificationRules('<link rel=stylesheet type="text/css" href=""/>');
        $result2 = $this->whitespaceMinifier->runMinificationRules('<link rel=stylesheet type="text/css" href="" />');
        $expected = '<link rel=stylesheet type="text/css" href="">';
        $this->assertEquals($expected, $result1);
        $this->assertEquals($expected, $result2);
    }

    public function testSpaceBetweenTags()
    {
        $result = $this->whitespaceMinifier->runMinificationRules('<p> </p>');
        $this->assertEquals('<p></p>', $result);
    }

    public function testMultipleSpaces()
    {
        $result = $this->whitespaceMinifier->runMinificationRules('<p>  Lorum  Ipsum  </p>');
        $this->assertEquals('<p>Lorum Ipsum</p>', $result);
    }

    public function testSpacesAroundPlaceholders()
    {
        $placeholderContainer = new PlaceholderContainer();
        $placeholder = $placeholderContainer->addPlaceholder(null);
        $result1 = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href=" '.$placeholder.'"></a>');
        $result2 = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href="'.$placeholder.' "></a>');
        $result3 = $this->whitespaceMinifier->removeSpacesAroundPlaceholders('<a href=\' '.$placeholder.' \'></a>');
        $expected = '<a href="'.$placeholder.'"></a>';
        $this->assertEquals($expected, $result1);
        $this->assertEquals($expected, $result2);
        $this->assertEquals('<a href=\''.$placeholder.'\'></a>', $result3);
    }

    public function testMaxLineLength()
    {
        $maxLineLength = 25;
        $result = $this->whitespaceMinifier->maxHtmlLineLength('This should break at <p></p> This should break at <p></p>',
            $maxLineLength);
        $this->assertEquals("This should break at <p>\n</p> This should break at <p>\n</p>", $result);
    }
}
