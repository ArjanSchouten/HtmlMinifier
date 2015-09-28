<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders;

use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Repositories\HtmlInlineElementsRepository;

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
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process($context)
    {
        $contents = $context->getContents();

        $contents = $this->whitespaceBetweenInlineElements($contents, $context->getPlaceholderContainer());
        $contents = $this->whitespaceInInlineElements($contents, $context->getPlaceholderContainer());
        $contents = $this->replaceElements($contents, $context->getPlaceholderContainer());

        return $context->setContents($contents);
    }

    /**
     * Whitespaces between inline html elements must be replaced with a placeholder because
     * a browser is showing that whitespace.
     *
     * @param string $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function whitespaceBetweenInlineElements($contents, PlaceholderContainer $placeholderContainer)
    {
        $elementsRegex = $this->getInlineElementsRegex();

        return preg_replace_callback(
            '/
                (
                    <('.$elementsRegex.')       # Match the start tag and capture it
                    (?:(?!<\/\2>).*)            # Match everything without the end tag
                    <\/\2>                      # Match the captured elements end tag
                )
                \s+                             # Match minimal 1 whitespace between the elements
                <('.$elementsRegex.')           # Match the start of the next inline element
            /xi',
            function ($match) use ($placeholderContainer) {
                // Where going to respect one space between the inline elements.
                $placeholder = $placeholderContainer->addPlaceholder(' ');

                return $match[1].$placeholder.'<'.$match[3];
        }, $contents);
    }

    /**
     * Whitespaces in an inline element have a function so we replace it.
     *
     * @param string $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function whitespaceInInlineElements($contents, PlaceholderContainer $placeholderContainer)
    {
        $elementsRegex = $this->getInlineElementsRegex();

        return preg_replace_callback(
            '/
                (
                    <('.$elementsRegex.')   # Match an inline element
                    (?:(?!<\/\2>).*)        # Match everything except its end tag
                    <\/\2>                  # Match the end tag
                    \s+
                )
                <('.$elementsRegex.')       # Match starting tag
            /xis',
            function ($match) use ($placeholderContainer) {
                return $this->replaceWhitespacesInInlineElements($match[1], $placeholderContainer).'<'.$match[3];
            }, $contents);
    }

    /**
     * Replace the whitespaces in inline elements with a placeholder.
     *
     * @param string $element
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    private function replaceWhitespacesInInlineElements($element, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/>\s/', function ($match) use ($placeholderContainer) {
            return '>'.$placeholderContainer->addPlaceholder(' ');
        }, $element);
    }

    /**
     * @param string $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function replaceElements($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($this->htmlPlaceholderTags as $htmlTag) {
            $contents = $this->setHtmlTagPlaceholder($contents, $placeholderContainer, $htmlTag);
        }

        return $contents;
    }

    /**
     * Add placeholder for html tags with a placeholder to prevent data violation.
     *
     * @param string                                           $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @param string                                           $htmlTag
     *
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

    /**
     * Get the regular expression for matching the inline element names.
     *
     * @return string
     */
    private function getInlineElementsRegex()
    {
        return 'a(?:bbr|cronym)?|b(?:do|ig|r|utton)?|c(?:ite|ode)|dfn|em|i(?:mg|nput)|kbd|label|map|object|q|s(?:amp|elect|mall|pan|trong|u[bp])|t(?:extarea|t)|var';
    }
}
