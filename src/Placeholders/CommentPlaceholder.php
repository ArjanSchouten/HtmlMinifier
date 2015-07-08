<?php

namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class CommentPlaceholder implements PlaceholderInterface
{
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
        $context->setContents($this->setCDataPlaceholder($context->getContents(), $context->getPlaceholderContainer()));

        return $context->setContents($this->setConditionalCommentsPlaceholder($context->getContents(), $context->getPlaceholderContainer()));
    }

    protected function setCDataPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/<!\[CDATA\[((?!\]\]>).)*\]\]>/s', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }

    /**
     * Replace conditional placeholders used by IE.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
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
