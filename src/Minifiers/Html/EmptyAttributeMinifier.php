<?php

namespace ArjanSchouten\HtmlMinifier\Minifiers\Html;

use ArjanSchouten\HtmlMinifier\Constants;
use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\Options;
use ArjanSchouten\HtmlMinifier\Repositories\HtmlBooleanAttributeRepository;
use ArjanSchouten\HtmlMinifier\Util\Html;

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
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process(MinifyContext $context)
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
     * @param \ArjanSchouten\HtmlMinifier\Minifiers\Html\HtmlBooleanAttributeRepository $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides()
    {
        return Options::EMPTY_ATTRIBUTES;
    }
}
