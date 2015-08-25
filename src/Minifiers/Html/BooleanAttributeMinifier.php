<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\Options;
use ArjanSchouten\HTMLMin\Repositories\HtmlBooleanAttributeRepository;

class BooleanAttributeMinifier implements MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process(MinifyContext $context)
    {
        $booleanAttributes = new HtmlBooleanAttributeRepository();
        $booleanAttributes = implode('|', $booleanAttributes->getAttributes()->all());

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
            /xi', function($match) {
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