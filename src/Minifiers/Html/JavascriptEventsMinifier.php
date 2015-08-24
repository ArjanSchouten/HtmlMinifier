<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\Options;

class JavascriptEventsMinifier implements MinifierInterface
{
    /**
     * Minify javascript prefixes on html event attributes.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process(MinifyContext $context)
    {
        $contents = preg_replace_callback(
            '/'.
                Constants::$htmlEventNamePrefix.Constants::ATTRIBUTE_NAME_REGEX.'   # Match an on{attribute}
                \s*=\s*             # Match equals sign with optional whitespaces around it
                ["\']?              # Match an optional quote
                \s*javascript:      # Match the text "javascript:" which should be removed
            /xis',
            function ($match) {
                return str_replace('javascript:', '', $match[0]);
            }, $context->getContents());

        return $context->setContents($contents);
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides()
    {
        return Options::REMOVE_DEFAULTS;
    }
}
