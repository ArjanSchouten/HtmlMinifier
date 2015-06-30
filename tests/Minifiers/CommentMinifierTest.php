<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;

class CommentMinifierTest extends PHPUnit_Framework_TestCase
{

    private $commentMinifier;

    public function __construct()
    {
        $this->commentMinifier = new CommentMinifier();
    }

    public function testCommentMinifier()
    {
        $result = $this->commentMinifier->minify('<div><!--TEST--></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->commentMinifier->minify('<div><!-- ' . PHP_EOL . ' TEST ' . PHP_EOL . ' --></div>');
        $this->assertEquals('<div></div>', $result);

        $result = $this->commentMinifier->minify("<div><!--[if IE]TEST<![ENDIF]--></div>");
        $this->assertEquals('<div></div>', $result);

        $result = $this->commentMinifier->minify("<div><!----></div>");
        $this->assertEquals('<div></div>', $result);

        $result = $this->commentMinifier->minify("<div><!-- test -- test --></div>");
        $this->assertEquals('<div></div>', $result);
    }

    public function testEmptyComment()
    {
        $result = $this->commentMinifier->minify("<div><!></div>");
        $this->assertEquals('<div></div>', $result);
    }
}