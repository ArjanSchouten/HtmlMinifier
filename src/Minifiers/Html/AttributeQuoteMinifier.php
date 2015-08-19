<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\ProvidesConstants;
use Illuminate\Support\Str;

class AttributeQuoteMinifier implements MinifierInterface
{
    /**
     * Chars which are prohibited from an unquoted attribute.
     *
     * @var array
     */
    protected $prohibitedChars = [
        '\'',
        '"',
        "\n",
        '=',
        '&',
        '`',
        '>',
        '<',
        ' ',
    ];

    /**
     * Execute the minification rule.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     *
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process($context)
    {
        return $context->setContents(preg_replace_callback(
            '/
                =           # start matching by a equal sign
                \s*         # its valid to use whitespaces after the equals sign
                (["\'])?    # match a single or double quote and make it a capturing group for backreferencing
                    (
                            (?:(?=\1)|[^\\\\])*             # normal part of "unrolling the loop". Match no quote nor escaped char
                            (?:\\\\\1                       # match the escaped quote
                                (?:(?=\1)|[^\\\\])*         # normal part again
                            )*                              # special part of "unrolling the loop"
                    )       # use a the "unrolling the loop" technique to be able to skip escaped quotes like ="\""
                \1?         # match the same quote symbol as matched before
            /x', function ($match) {
            return $this->minifyAttribute($match);
        }, $context->getContents()));

        return $context->setContents(preg_replace_callback('/=\s*\'([^\\\\\']*(?:\\\\\'[^\\\\\']*)*)\'/', function ($match) {
            return $this->minifyAttribute($match);
        }, $context->getContents()));
    }

    /**
     * Minify the attribute quotes if allowed.
     *
     * @param array $match
     *
     * @return string
     */
    protected function minifyAttribute($match)
    {
        if (Str::contains($match[2], $this->prohibitedChars)) {
            return $match[0];
        } elseif (preg_match('/'.Constants::PLACEHOLDER_PATTERN.'/is', $match[2])) {
            return $match[0];
        }

        return '='.$match[2];
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string if an option is needed, return the option name
     */
    public function provides()
    {
        return ProvidesConstants::ATTRIBUTE_QUOTES;
    }
}
