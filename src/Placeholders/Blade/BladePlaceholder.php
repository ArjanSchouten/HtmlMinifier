<?php

namespace ArjanSchouten\HTMLMin\Placeholders\Blade;

use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\Placeholders\PlaceholderInterface;

class BladePlaceholder implements PlaceholderInterface
{
    protected $bladeReplacements = [
        'Blade',
        'Echos',
    ];

    protected $tags = [
        ['{{--', '--}}'],
        ['{{{', '}}}'],
        ['{!!', '!!}'],
        ['{{', '}}'],
    ];

    /**
     * Replace critical content with a temp placeholder for integrity.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    public function process($context)
    {
        $contents = $this->setEchosPlaceholder($context->getContents(), $context->getPlaceholderContainer());

        return $context->setContents($this->setBladeControlStructuresPlaceholder($contents, $context->getPlaceholderContainer()));
    }

    /**
     * Add placeholder for blade echo statements.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setEchosPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($this->tags as $tag) {
            $pattern = sprintf('/%s\s*(.+?)\s*%s(\r?\n)?/s', $tag[0], $tag[1]);
            $contents = preg_replace_callback($pattern, function ($match) use ($placeholderContainer) {
                return $placeholderContainer->addPlaceholder($match[0]);
            }, $contents);
        }

        return $contents;
    }

    /**
     * Add placeholder for blade specific control structures.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setBladeControlStructuresPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/@\w*(\s*\(.*\))?/', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }
}
