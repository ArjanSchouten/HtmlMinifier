<?php

use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\Placeholders\PHP\PHPPlaceholder;
use Mockery as m;

class PHPPlaceholderTest extends PHPUnit_Framework_TestCase
{

    private $phpPlaceholder;

    public function setUp()
    {
        $this->phpPlaceholder = new PHPPlaceholder();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testPHPPlaceholder()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyPipelineContext($placeholderContainer);

        $result = $this->phpPlaceholder->process($context->setContents('<?php echo \''.PHP_EOL.'\';?>'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->phpPlaceholder->process($context->setContents('<?php echo \''.PHP_EOL.'\';?><?php echo \''.PHP_EOL.'\';?>'));
        $this->assertEquals($placeholder.$placeholder, $result->getContents());

        $result = $this->phpPlaceholder->process($context->setContents('<?php echo \''.PHP_EOL.'\';'));
        $this->assertEquals($placeholder, $result->getContents());
    }

    public function testShortTags()
    {
        $placeholder = 'myPlaceholder';
        $placeholderContainer = m::mock(PlaceholderContainer::class)->shouldReceive('addPlaceholder')->andReturn($placeholder)->getMock();
        $context = new MinifyPipelineContext($placeholderContainer);

        $result = $this->phpPlaceholder->process($context->setContents('<?= echo \'\';?>'));
        $this->assertEquals($placeholder, $result->getContents());

        $result = $this->phpPlaceholder->process($context->setContents('<p>This is a test</p><?= echo \''.PHP_EOL.'\';?><p>end</p>'));
        $this->assertEquals('<p>This is a test</p>'.$placeholder.'<p>end</p>', $result->getContents());
    }
}