<?php

use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class RedundantAttributeMinifierTest extends PHPUnit_Framework_TestCase
{

    private $redundantAttributeFileMinifier;

    public function __construct()
    {
        $this->redundantAttributeFileMinifier = new RedundantAttributeMinifier();
    }

    public function testAttributeRemoval()
    {
        $context = new MinifyPipelineContext(new PlaceholderContainer());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script language="javascript"></script>'));
        $this->assertEquals('<script></script>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script language=\'javascript\'></script>'));
        $this->assertEquals('<script></script>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script id="my-id" language=\'javascript\'></script>'));
        $this->assertEquals('<script id="my-id"></script>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script id="my-id" language=' . PHP_EOL . '\'javascript\'></script>'));
        $this->assertEquals('<script id="my-id"></script>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script id="test" language=\'javascript\' type="text/javascript" class="test"></script>'));
        $this->assertEquals('<script id="test" class="test"></script>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<form method=get></form>'));
        $this->assertEquals('<form></form>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<form method=get action="test"></form>'));
        $this->assertEquals('<form action="test"></form>', $result->getContents());

        $result = $this->redundantAttributeFileMinifier->process($context->setContents('<script id="javascriptElement" language="javascript" type="text/javascript" class="test"></script>'));
        $this->assertEquals('<script id="javascriptElement" class="test"></script>', $result->getContents());
    }
}
