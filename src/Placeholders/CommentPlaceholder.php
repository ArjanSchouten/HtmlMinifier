<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders;

use ArjanSchouten\HtmlMinifier\PlaceholderContainer;

class CommentPlaceholder implements PlaceholderInterface
{
    /**
     * Replace critical content with a temp placeholder for integrity.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process($context)
    {
        $context->setContents($this->setCDataPlaceholder($context->getContents(), $context->getPlaceholderContainer()));

        return $context->setContents($this->setConditionalCommentsPlaceholder($context->getContents(), $context->getPlaceholderContainer()));
    }

    /**
     * Replace CData with a temporary placeholder.
     *
     * @param string                                      $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @return string
     */
    protected function setCDataPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/<!\[CDATA\[((?!\]\]>).)*\]\]>/s', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }

    /**
     * Replace conditional placeholders used by Internet Explorer.
     *
     * @param string                                      $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @return string
     */
    protected function setConditionalCommentsPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        $contents = preg_replace_callback('/<!\[((?!\]>).)*\]>((?!<!\[endif\]>).)*<!\[endif\]>/is',
            function ($match) use ($placeholderContainer) {
                return $placeholderContainer->addPlaceholder($match[0]);
            }, $contents);

        return preg_replace_callback('/<!-{2}\[((?!\]>).)*\]>((?!<!\[endif\]-{2}>).)*<!\[endif\]-{2}>/is',
            function ($match) use ($placeholderContainer) {
                return $placeholderContainer->addPlaceholder($match[0]);
            }, $contents);
    }
}
