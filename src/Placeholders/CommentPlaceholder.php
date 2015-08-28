<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders;

use ArjanSchouten\HtmlMinifier\PlaceholderContainer;

class CommentPlaceholder implements PlaceholderInterface
{
    /**
     * Replace critical content with a temp placeholder for integrity.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
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
     * @param string                                           $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
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
     * @param string                                           $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setConditionalCommentsPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback(
            '/
                (
                    <!                  # Match the start of a comment
                    (-{2})?             # IE can understand comments without dashes
                    \[                  # Match the start ("[" is a metachar => escape it)
                        (?:(?!\]>).)*   # Match everything except "]>"
                    \]>                 # Match end
                )
                (
                    (?:
                        (?!<!\[endif\]-{2}?>)
                    .)*
                )                     # Match everything except end of conditional comment
                (
                    <!\[endif\]
                          (?:\2|(?=>))  # Use a trick to ensure that when dashes are captured they are...
                                        # matched at the end! Else make sure that the next char is a ">"!
                    >                   # Match the endif with the captured dashes
                )
            /xis',
            function ($match) use ($placeholderContainer) {
                var_dump($match);
                if (!empty(preg_replace('/\s*/', '', $match[3]))) {
                    return $placeholderContainer->addPlaceholder($match[1]).$match[3].$placeholderContainer->addPlaceholder($match[4]);
                } else {
                    return '';
                }
            }, $contents);
    }
}
