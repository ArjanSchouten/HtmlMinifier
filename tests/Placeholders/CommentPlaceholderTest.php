<?php

use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Placeholders\CommentPlaceholder;
use Mockery as m;

class CommentPlaceholderTest extends PHPUnit_Framework_TestCase
{
    private $commentPlaceholder;

    public function setUp()
    {
        $this->commentPlaceholder = new CommentPlaceholder();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testConditionalCommentPlaceholder()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyContext($placeholderContainer);

        $result = $this->commentPlaceholder->process($context->setContents('<!--[if IE 6]><![endif]-->'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->commentPlaceholder->process($context->setContents('<!--[if IE 6]>'.str_repeat(PHP_EOL,
                5).'<![endif]-->'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->commentPlaceholder->process($context->setContents('<div>test</div><!--[if IE 6]>'.str_repeat(PHP_EOL,
                5).'<![endif]-->'));
        $this->assertEquals('<div>test</div>'.$placeholder, $result->getContents());

        $result = $this->commentPlaceholder->process($context->setContents('<![if IE 6]>'.str_repeat(PHP_EOL,
                5).'<![endif]>'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->commentPlaceholder->process($context->setContents('<!--[if (gte IE 5.5)&(lt IE 7)]><p>You are using IE 5.5 or IE 6.</p><![endif]-->'));
        $this->assertEquals($placeholder, $result->getContents());
    }

    public function testNestedConditionalComments()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyContext($placeholderContainer);

        $result = $this->commentPlaceholder->process($context->setContents('<!--[if true]><![if IE 7]><p>This nested comment is displayed in IE 7.</p><![endif]><![endif]-->'));
        $this->assertEquals($placeholder, $result->getContents());
    }

    public function testCData()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyContext($placeholderContainer);

        $result = $this->commentPlaceholder->process($context->setContents('<![CDATA[<greeting>Hello, world!</greeting>]]>'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->commentPlaceholder->process($context->setContents('<![CDATA[<greeting>Hello,'.str_repeat(PHP_EOL,
                5).' world!</greeting>]]>'));
        $this->assertEquals($placeholder, $result->getContents());

        //nested CDATA
        $result = $this->commentPlaceholder->process($context->setContents('<foo><![CDATA[<![CDATA[]]]]><![CDATA[>]]></foo>'));
        $this->assertEquals('<foo>'.$placeholder.$placeholder.'</foo>', $result->getContents());
    }
}
