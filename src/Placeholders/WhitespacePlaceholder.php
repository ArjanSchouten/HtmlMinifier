<?php

namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class WhitespacePlaceholder implements PlaceholderInterface
{
    protected $htmlPlaceholderTags = [
        'plaintext',
        'textarea',
        'listing',
        'script',
        'style',
        'code',
        'cite',
        'pre',
        'xmp',
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
        $contents = $context->getContents();

        foreach ($this->htmlPlaceholderTags as $htmlTag) {
            $contents = $this->setHtmlTagPlaceholder($contents, $context->getPlaceholderContainer(), $htmlTag);
        }

        return $context->setContents($contents);
    }

    /**
     * Add placeholder for html tags with a placeholder to prevent data violation.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     * @param string $htmlTag
     *
     * @return string
     */
    protected function setHtmlTagPlaceholder($contents, PlaceholderContainer $placeholderContainer, $htmlTag)
    {
        $pattern = '/(<'.$htmlTag.'((?=([^"]*".[^"]*")*[^"]*)[^>]*>)*)(((?!<\/'.$htmlTag.'>).)*)(<\/'.$htmlTag.'>)/is';

        return preg_replace_callback($pattern, function ($match) use ($placeholderContainer) {
            return $match[1].$placeholderContainer->addPlaceholder($match[3]).$match[6];
        }, $contents);
    }
}
