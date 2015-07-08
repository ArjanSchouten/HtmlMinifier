<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class CommentMinifierTest extends PHPUnit_Framework_TestCase
{
    private $commentMinifier;

    public function __construct()
    {
        $this->commentMinifier = new CommentMinifier();
    }

    public function testCommentMinifier()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->commentMinifier->process($context->setContents('<div><!--TEST--></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->commentMinifier->process($context->setContents('<div><!-- '.PHP_EOL.' TEST '.PHP_EOL.' --></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->commentMinifier->process($context->setContents('<div><!--[if IE]TEST<![ENDIF]--></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->commentMinifier->process($context->setContents('<div><!----></div>'));
        $this->assertEquals('<div></div>', $result->getContents());

        $result = $this->commentMinifier->process($context->setContents('<div><!-- test -- test --></div>'));
        $this->assertEquals('<div></div>', $result->getContents());
    }

    public function testEmptyComment()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->commentMinifier->process($context->setContents('<div><!></div>'));
        $this->assertEquals('<div></div>', $result->getContents());
    }
}
