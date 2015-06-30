<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use Illuminate\Support\Str;

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
    public function minify($contents)
    {
        $contents = preg_replace_callback('/=\s*"([^"]*(?:[\\"]+[^"]*)*)"/', function ($match) {
            return $this->replaceWith($match);
        }, $contents);

        return preg_replace_callback('/=\s*\'([^\']*(?:[\\\']+[^\']*)*)\'/', function ($match) {
            return $this->replaceWith($match);
        }, $contents);
    }

    protected function replaceWith($match)
    {
        if (Str::contains($match[1], $this->prohibitedCharsUnquotedAttribute)) {
            return $match[0];
        }

        return '=' . $match[1];

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