<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\Util\Html;
use Illuminate\Support\Collection;

class RedundantAttributeMinifier implements MinifierInterface
{

    protected $redundantAttributes = [
        'script' => [
            'type' => 'text\/javascript',
            'language' => 'javascript',
        ],
        'link' => [
            'type' => 'text\/css',
        ],
        'style' => [
            'type' => 'text\/css',
        ],
        'form' => [
            'method' => 'get',
        ]
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
        Collection::make($this->redundantAttributes)->each(function ($attributes, $element) use (&$contents) {
            Collection::make($attributes)->each(function ($value, $attribute) use ($element, &$contents) {
                $contents = preg_replace_callback('/' . $element . '((?!\s*' . $attribute . '\s*=).)*(\s*' . $attribute . '\s*=\s*"?\'?\s*' . $value . '\s*"?\'?\s*)/is',
                    function ($match) {
                        return $this->removeAttribute($match[0], $match[2]);
                    }, $contents);
            });
        });

        return $contents;
    }

    protected function removeAttribute($element, $attribute)
    {
        $replacement = Html::hasSurroundingAttributes($attribute) ? ' ' : '';

        return str_replace($attribute, $replacement, $element);
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides()
    {
        return false;
    }
}