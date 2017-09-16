<?php

use ArjanSchouten\HtmlMinifier\Statistics\Statistics;

class StatisticsTest extends PHPUnit_Framework_TestCase {
    public function testCreateStatistics() {
        $contents = '<html><head></head><body>This is a test</body></html>';
        $statistics = new Statistics($contents);

        $this->assertCount(1, $statistics->getReferencePoints());
    }

    public function testGetBytesSaved() {
        $inputSize = 200;
        $firstStepSize = 180;
        $expectedByteSavings = 20;

        $contents = str_random($inputSize);
        $statistics = new Statistics($contents);

        $statistics->createReferencePoint($firstStepSize, 'Test step');

        $this->assertEquals($expectedByteSavings, $statistics->getTotalSavedBytes());
    }
}