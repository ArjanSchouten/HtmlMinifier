<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Str;
use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

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
     * @param  \ArjanSchouten\HTMLMin\MinifyPipelineContext  $context
     * @return \ArjanSchouten\HTMLMin\MinifyPipelineContext
     */
    public function process($context)
    {
        $context->setContents(preg_replace_callback('/=\s*"([^\\\\"]*(?:\\\\"[^\\\\"]*)*)"/', function ($match) {
            return $this->minifyAttribute($match);
        }, $context->getContents()));

        return $context->setContents(preg_replace_callback('/=\s*\'([^\\\\\']*(?:\\\\\'[^\\\\\']*)*)\'/', function ($match) {
            return $this->minifyAttribute($match);
        }, $context->getContents()));
    }

    /**
     * Minify the attribute quotes if allowed.
     *
     * @param  array  $match
     * @return string
     */
    protected function minifyAttribute($match)
    {
        if (Str::contains($match[1], $this->prohibitedChars)) {
            return $match[0];
        } elseif (preg_match('/'.Constants::PLACEHOLDER_PATTERN.'/is', $match[1])) {
            return $match[0];
        }

        return '='.$match[1];
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string if an option is needed, return the option name
     */
    public function provides()
    {
        return 'remove-attributequotes';
    }
}
