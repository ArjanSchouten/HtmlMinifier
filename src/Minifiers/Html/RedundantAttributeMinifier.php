<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Collection;
use ArjanSchouten\HTMLMin\Util\Html;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

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
    public function process($context)
    {
        Collection::make($this->redundantAttributes)->each(function ($attributes, $element) use (&$context) {
            Collection::make($attributes)->each(function ($value, $attribute) use ($element, &$context) {
                $context->setContents(preg_replace_callback('/'.$element.'((?!\s*'.$attribute.'\s*=).)*(\s*'.$attribute.'\s*=\s*"?\'?\s*'.$value.'\s*"?\'?\s*)/is',
                    function ($match) {
                        return $this->removeAttribute($match[0], $match[2]);
                    }, $context->getContents()));
            });
        });

        return $context;
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
