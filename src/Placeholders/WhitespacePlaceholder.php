<?php

namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class WhitespacePlaceholder implements PlaceholderInterface
{
    /**
     * @var array
     */
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
     * Replace critical content with a temporary placeholder.
     *
     * @param  \ArjanSchouten\HTMLMin\MinifyContext  $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
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
     * @param  string  $contents
     * @param  \ArjanSchouten\HTMLMin\PlaceholderContainer  $placeholderContainer
     * @param  string $htmlTag
     * @return string
     */
    protected function setHtmlTagPlaceholder($contents, PlaceholderContainer $placeholderContainer, $htmlTag)
    {
        // Attributes may contain a ">" which should be skipped due to this unrolling the loop.
        $pattern = '/(<'.$htmlTag.'(?:[^"\'>]*|"[^"]*"|\'[^\']*\')*>)(((?!<\/'.$htmlTag.'>).)*)(<\/'.$htmlTag.'>)/is';

        return preg_replace_callback($pattern, function ($match) use ($placeholderContainer) {
            return $match[1].$placeholderContainer->addPlaceholder($match[2]).$match[4];
        }, $contents);
    }
}
