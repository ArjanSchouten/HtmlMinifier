<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\ProvidesConstants;

class JavascriptEventsMinifier implements MinifierInterface
{
    /**
     * Minify javascript prefixes on html event attributes.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     *
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process($context)
    {
        $contents = preg_replace_callback('/'.Constants::$htmlEventNamePrefix.Constants::ATTRIBUTE_NAME_REGEX.'\s*=\s*"?\'?\s*javascript:/is',
            function ($match) {
                return str_replace('javascript:', '', $match[0]);
            }, $context->getContents());

        return $context->setContents($contents);
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string if an option is needed, return the option name
     */
    public function provides()
    {
        return ProvidesConstants::REMOVE_DEFAULTS;
    }
}
