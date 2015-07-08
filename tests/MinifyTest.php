<?php

use ArjanSchouten\HTMLMin\Minify;
use Mockery\CountValidator\Exception;

class MinifyTest extends PHPUnit_Framework_TestCase
{
    public function testBuildPipeline()
    {
        $minify = new Minify();

        try {
            $minify->buildPipeline([]);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }
}