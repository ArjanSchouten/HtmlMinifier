<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\Util\Html;

class EmptyAttributeMinifier implements MinifierInterface
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new HtmlBooleanAttributeRepository();
    }

    /**
     * Execute the minification rule.
     *
     * @param string $contents
     *
     * @return string
     */
    public function minify($contents)
    {
        return preg_replace_callback('/(\s*' . Constants::ATTRIBUTE_NAME_REGEX . '\s*)=\s*["\']\s*["\']\s*/',
            function ($match) {
                if ($this->isBooleanAttribute($match[1])) {
                    return Html::isLastAttribute($match[0]) ? $match[1] : $match[1] . ' ';
                }

                return Html::hasSurroundingAttributes($match[0]) ? ' ' : '';
            }, $contents);
    }

    private function isBooleanAttribute($attribute)
    {
        return $this->repository->getAttributes()->search(trim($attribute)) || Html::isDataAttribute($attribute);
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides()
    {
        return 'remove-empty-attributes';
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
}