<?php

namespace ArjanSchouten\HTMLMin;

use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\CommentPlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\PHP\PHPPlaceholder;
use ArjanSchouten\HTMLMin\Placeholders\WhitespacePlaceholder;

class Minify
{
    private $placeholders = [
        PHPPlaceholder::class,
        BladePlaceholder::class,
        CommentPlaceholder::class,
        WhitespacePlaceholder::class,
    ];

    private $minifiers = [
        CommentMinifier::class,
        WhitespaceMinifier::class,
        AttributeQuoteMinifier::class,
        EmptyAttributeMinifier::class,
        JavascriptEventsMinifier::class,
        RedundantAttributeMinifier::class,
    ];

    /**
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @param array $options
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
     * @param array $options
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    protected function minifiers(MinifyContext $context, $options = [])
    {
        foreach ($this->minifiers as $minifier) {
            $minifier = new $minifier();

            $provides = $minifier->provides();
            if ($provides === false || (isset($options[$provides]) && $options[$provides] === true)) {
                $context = $minifier->process($context);
            }
        }

        return $context;
    }

    /**
     * Restore placeholders with their original content.
     *
     * @param  \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function restore(MinifyContext $context)
    {
        $withoutPlaceholders = $context->getPlaceholderContainer()->restorePlaceholders($context->getContents());

        return $context->setContents($withoutPlaceholders);
    }
}
