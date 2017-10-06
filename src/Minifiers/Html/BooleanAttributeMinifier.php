<?php

namespace ArjanSchouten\HtmlMinifier\Minifiers\Html;

use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\Options;
use ArjanSchouten\HtmlMinifier\Repositories\HtmlBooleanAttributeRepository;

class BooleanAttributeMinifier implements MinifierInterface
{
    /**
     * Execute the minification rule.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process(MinifyContext $context)
    {
        $booleanAttributes = implode('|', (new HtmlBooleanAttributeRepository())->getAttributes());

        return $context->setContents(preg_replace_callback(
            '/
                \s                          # first match a whitespace which is an indication if its an attribute
                ('.$booleanAttributes.')    # match and capture a boolean attribute
                \s*
                =
                \s*
                ([\'"])?                    # optional to use a quote
                (\1|true|false|([\s>"\']))    # match the boolean attribute name again or true|false
                \2?                         # match the quote again
            /xi', function ($match) {
                if (isset($match[4])) {
                    return ' '.$match[1];
                }

                if ($match[3] == 'false') {
                    return '';
                }

                return ' '.$match[1];
            }, $context->getContents()));
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides()
    {
        return Options::BOOLEAN_ATTRIBUTES;
    }
}
