<?php

namespace ArjanSchouten\HtmlMinifier;

use ArjanSchouten\HtmlMinifier\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\BooleanAttributeMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\OptionalElementMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\RedundantAttributeMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\Placeholders\Blade\BladePlaceholder;
use ArjanSchouten\HtmlMinifier\Placeholders\CommentPlaceholder;
use ArjanSchouten\HtmlMinifier\Placeholders\Php\PhpPlaceholder;
use ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface;
use ArjanSchouten\HtmlMinifier\Placeholders\WhitespacePlaceholder;
use InvalidArgumentException;

class Minify
{
    /**
     * @var \ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface[]
     */
    protected static $placeholders = [
        PhpPlaceholder::class,
        BladePlaceholder::class,
        CommentPlaceholder::class,
        WhitespacePlaceholder::class,
    ];

    /**
     * @var \ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface[]
     */
    protected static $minifiers = [
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
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @param array                                     $options
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function run(MinifyContext $context, $options = [])
    {
        $context->addMeasurementStep($context->getContents(), 'Initial step');
        $context = $this->placeholders($context);
        $context = $this->minifiers($context, $options);

        return $this->restore($context);
    }

    /**
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    protected function placeholders(MinifyContext $context)
    {
        foreach (self::$placeholders as $placeholder) {
            $placeholder = new $placeholder();
            $context = $placeholder->process($context);
        }

        return $context;
    }

    /**
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @param array                                     $options
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    protected function minifiers(MinifyContext $context, $options = [])
    {
        foreach (self::$minifiers as $minifier) {
            $minifier = new $minifier();

            $provides = $minifier->provides();
            if ($this->runAll($options) || $this->isOptionSet($provides, $options)) {
                $context = $minifier->process($context);
                $context->addMeasurementStep($context->getContents(), $this->getClassName($minifier).'|'.$minifier->provides());
            }
        }

        return $context;
    }

    /**
     * Checks if all minifiers should be runned.
     *
     * @param array $options
     *
     * @return bool
     */
    protected function runAll($options = [])
    {
        return isset($options[Options::ALL]) && $options[Options::ALL];
    }

    /**
     * Check whether an option is set in the options aray.
     *
     * @param string $provides
     * @param array  $options
     *
     * @return bool
     */
    protected function isOptionSet($provides, $options = [])
    {
        return (isset($options[$provides]) && $options[$provides] === true) || Options::options()[$provides]->isDefault();
    }

    /**
     * Restore placeholders with their original content.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     *
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    protected function restore(MinifyContext $context)
    {
        $withoutPlaceholders = $context->getPlaceholderContainer()->restorePlaceholders($context->getContents());

        return $context->setContents($withoutPlaceholders);
    }

    /**
     * @return \ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface[]
     */
    public static function getPlaceholders()
    {
        return self::$placeholders;
    }

    /**
     * Add a placeholder strategy to the registered placeholders.
     *
     * @param string $placeholder
     *
     * @return \ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface[]
     */
    public static function addPlaceholder($placeholder)
    {
        if (!isset(class_implements($placeholder)[PlaceholderInterface::class])) {
            throw new InvalidArgumentException('The class ['.$placeholder.'] should be a member of the ['.PlaceholderInterface::class.']');
        } elseif (in_array($placeholder, self::$placeholders)) {
            throw new InvalidArgumentException('The placeholder ['.$placeholder.'] is already added to the minifier!');
        }

        self::$placeholders[] = $placeholder;

        return self::$placeholders;
    }

    /**
     * @return \ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface[]
     */
    public static function getMinifiers()
    {
        return self::$minifiers;
    }

    /**
     * Add a placeholder strategy to the registered placeholders.
     *
     * @param string $minifier
     *
     * @return \ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface[]
     */
    public static function addMinifier($minifier)
    {
        if (!isset(class_implements($minifier)[MinifierInterface::class])) {
            throw new InvalidArgumentException('The class ['.$minifier.'] should be a member of the ['.MinifierInterface::class.']');
        } elseif (in_array($minifier, self::$minifiers)) {
            throw new InvalidArgumentException('The minifier ['.$minifier.'] is already added to the minifier!');
        }

        self::$minifiers[] = $minifier;

        return self::$minifiers;
    }

    private function getClassName($object)
    {
        $class = get_class($object);
        if (($index = strrpos($class, '\\'))) {
            $fixForTrailingBackslash = 1;
            return substr($class, $index + $fixForTrailingBackslash);
        }

        return $class;
    }
}
