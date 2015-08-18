<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Collection;
use ArjanSchouten\HTMLMin\Util\Html;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class RedundantAttributeMinifier implements MinifierInterface
{
    /**
     * Attributes which are not needed by the browser.
     *
     * @var array
     */
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
        ],
    ];

    /**
     * Minify redundant attributes which are not needed by the browser.
     *
     * @param  \ArjanSchouten\HTMLMin\MinifyContext  $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
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

    /**
     * Remove the attribute from the element.
     *
     * @param  string  $element
     * @param  string  $attribute
     * @return string
     */
    protected function removeAttribute($element, $attribute)
    {
        $replacement = Html::hasSurroundingAttributes($attribute) ? ' ' : '';

        return str_replace($attribute, $replacement, $element);
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return bool if an option is needed, return the option name
     */
    public function provides()
    {
        return false;
    }
}
