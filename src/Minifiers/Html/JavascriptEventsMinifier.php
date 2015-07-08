<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class JavascriptEventsMinifier implements MinifierInterface
{

    /**
     * Execute the minification rule.
     *
     * @param string $contents
     *
     * @return string
     */
    public function process($context)
    {
        return $context->setContents(preg_replace_callback('/' . Constants::$htmlEventNamePrefix . Constants::ATTRIBUTE_NAME_REGEX . '\s*=\s*"?\'?\s*javascript:/is',
            function ($match) {
                return str_replace('javascript:', '', $match[0]);
            }, $context->getContents()));
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides()
    {
        return 'remove-defaults';
    }
}
