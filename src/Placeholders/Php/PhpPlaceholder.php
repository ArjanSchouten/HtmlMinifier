<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders\Php;

use ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface;

class PhpPlaceholder implements PlaceholderInterface
{
    /**
     * Replace PHP tags with a temporary placeholder.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process($context)
    {
        $contents = $context->getContents();
        $contents = preg_replace_callback('/<\?=((?!\?>).)*\?>/s', function ($match) use ($context) {
            return $context->getPlaceholderContainer()->addPlaceholder($match[0]);
        }, $contents);
        $contents = preg_replace_callback('/<\?php((?!\?>).)*(\?>)?/s', function ($match) use ($context) {
            return $context->getPlaceholderContainer()->addPlaceholder($match[0]);
        }, $contents);

        return $context->setContents($contents);
    }
}
