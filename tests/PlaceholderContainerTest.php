<?php

use ArjanSchouten\HtmlMinifier\Constants;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;

class PlaceholderContainerTest extends PHPUnit_Framework_TestCase {
    public function testReplaceRestorePlaceholder(){
        $contentToReplace = 'special';
        $input = 'Original contents with '.$contentToReplace.' input.';
        $placeholderContainer = new PlaceholderContainer();
        $result = $placeholderContainer->addPlaceholder($contentToReplace, $input);
        $restoredInput = $placeholderContainer->restorePlaceholders($result);

        $this->assertEquals($input, $restoredInput);
        $this->assertNotEquals($input, $result);
    }

    public function testRemoveNestedPlaceholders() {
        $replace = 'placeholder';
        $placeholderContainer = new PlaceholderContainer();
        $placeholder = $placeholderContainer->createPlaceholder($replace);
        $originalContent = 'Content to replace with nested placeholder: '.$placeholder;
        $placeholderContainer->createPlaceholder($originalContent);

        $this->assertEquals(1, $placeholderContainer->count());
        $this->assertEquals($originalContent, $originalContent);
    }

    public function testIdenticalPlaceholders() {
        $content = 'This is a test. This is a test';
        $placeholderContainer = new PlaceholderContainer();
        $content = $placeholderContainer->addPlaceholder('This is a test', $content);

        $this->assertCount(1, $placeholderContainer);
        $this->assertEquals(2, preg_match_all('/'.Constants::PLACEHOLDER_PATTERN.'/', $content));
    }

    public function testOriginalContentSize() {
        $placeholderContainer = new PlaceholderContainer();
        $input = '<html><head></head><body>Test content</body></html>';
        $inputSize = strlen($input);
        $result = $placeholderContainer->addPlaceholder('Test content', $input);
        $resultSize = strlen($result);

        $this->assertLessThan($resultSize, $inputSize);
        $this->assertEquals($inputSize, $placeholderContainer->getContentSize($result));
    }
}
