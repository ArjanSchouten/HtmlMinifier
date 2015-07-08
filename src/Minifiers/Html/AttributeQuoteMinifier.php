<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Str;
use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class AttributeQuoteMinifier implements MinifierInterface
{
    protected $prohibitedCharsUnquotedAttribute = [
        '\'',
        '"',
        "\n",
        '=',
        '&',
        '`',
        '>',
        '<',
    ];

    /**
     * Execute the minification rule.
     *
     * @param string $contents
     *
     * @return string
     */
    public function process($context)
    {
        $context->setContents(preg_replace_callback('/=\s*"([^\\\\"]*(?:\\\\"[^\\\\"]*)*)"/', function ($match) {
            return $this->replaceWith($match);
        }, $context->getContents()));

        return $context->setContents(preg_replace_callback('/=\s*"([^\\\\\']*(?:\\\\\'[^\\\\\']*)*)"/', function ($match) {
            return $this->replaceWith($match);
        }, $context->getContents()));
    }

    protected function replaceWith($match)
    {
        if (Str::contains($match[1], $this->prohibitedCharsUnquotedAttribute)) {
            return $match[0];
        } elseif (preg_match('/'.Constants::PLACEHOLDER_PATTERN.'/is', $match[1])) {
            return $match[0];
        }

        return '='.$match[1];
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides()
    {
        return 'remove-attributequotes';
    }
}
