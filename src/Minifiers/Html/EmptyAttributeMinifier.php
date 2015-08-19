<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\ProvidesConstants;
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
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     *
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process($context)
    {
        return $context->setContents(preg_replace_callback(
            '/
                (\s*'.Constants::ATTRIBUTE_NAME_REGEX.'\s*)     # Match the attribute name
                =\s*                                            # Match the equal sign with optional whitespaces
                (["\'])                                         # Match quotes and capture for backreferencing
                \s*                                             # Strange but possible to have a whitespace in an attribute
                \2                                              # Backreference to the matched quote
                \s*
            /x',
            function ($match) {
                if ($this->isBooleanAttribute($match[1])) {
                    return Html::isLastAttribute($match[0]) ? $match[1] : $match[1].' ';
                }

                return Html::hasSurroundingAttributes($match[0]) ? ' ' : '';
            }, $context->getContents()));
    }

    /**
     * Check if an attribute is a boolean attribute.
     *
     * @param string $attribute
     *
     * @return bool
     */
    private function isBooleanAttribute($attribute)
    {
        return $this->repository->getAttributes()->search(trim($attribute)) || Html::isDataAttribute($attribute);
    }

    /**
     * @param \ArjanSchouten\HTMLMin\Minifiers\Html\HtmlBooleanAttributeRepository $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string if an option is needed, return the option name
     */
    public function provides()
    {
        return ProvidesConstants::EMPTY_ATTRIBUTES;
    }
}
