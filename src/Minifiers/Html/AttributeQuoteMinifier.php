<?php

namespace ArjanSchouten\HtmlMinifier\Minifiers\Html;

use ArjanSchouten\HtmlMinifier\Constants;
use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\Options;
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
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process(MinifyContext $context)
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
     * @return string
     */
    public function provides()
    {
        return Options::ATTRIBUTE_QUOTES;
    }
}
