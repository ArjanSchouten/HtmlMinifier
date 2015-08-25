<?php

namespace ArjanSchouten\HTMLMin;

use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\BooleanAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\OptionalElementMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\CommentPlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\PHP\PhpPlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\WhitespacePlaceholder;

class Minify
{
    private $placeholders = [
        PhpPlaceholder::class,
        BladePlaceholder::class,
        CommentPlaceholder::class,
        WhitespacePlaceholder::class,
    ];

    private $minifiers = [
        CommentMinifier::class,
        WhitespaceMinifier::class,
        AttributeQuoteMinifier::class,
        EmptyAttributeMinifier::class,
        OptionalElementMinifier::class,
        BooleanAttributeMinifier::class,
        JavascriptEventsMinifier::class,
        RedundantAttributeMinifier::class,
    ];

    /**
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @param array                                $options
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function run(MinifyContext $context, $options = [])
    {
        $context = $this->placeholders($context);
        $context = $this->minifiers($context, $options);

        return $this->restore($context);
    }

    /**
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    protected function placeholders(MinifyContext $context)
    {
        foreach ($this->placeholders as $placeholder) {
            $placeholder = new $placeholder();
            $context = $placeholder->process($context);
        }

        return $context;
    }

    /**
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @param array                                $options
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    protected function minifiers(MinifyContext $context, $options = [])
    {
        foreach ($this->minifiers as $minifier) {
            $minifier = new $minifier();

            $provides = $minifier->provides();
            if ($this->runAll($options) || $this->isOptionSet($provides, $options)) {
                $context = $minifier->process($context);
            }
        }

        return $context;
    }

    protected function runAll($options = [])
    {
        return isset($options[Options::ALL]) && $options[Options::ALL];
    }

    protected function isOptionSet($provides, $options = [])
    {
        return (isset($options[$provides]) && $options[$provides] === true) || Options::options()[$provides]->isDefault();
    }

    /**
     * Restore placeholders with their original content.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    protected function restore(MinifyContext $context)
    {
        $withoutPlaceholders = $context->getPlaceholderContainer()->restorePlaceholders($context->getContents());

        return $context->setContents($withoutPlaceholders);
    }
}
