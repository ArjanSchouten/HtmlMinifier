<?php

namespace ArjanSchouten\HtmlMinifier\Minifiers\Html;

use ArjanSchouten\HtmlMinifier\Minifiers\MinifierInterface;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\Options;
use ArjanSchouten\HtmlMinifier\Repositories\OptionalElementsRepository;

class OptionalElementMinifier implements MinifierInterface
{
    /**
     * Execute the minification rule.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process(MinifyContext $context)
    {
        $elements = (new OptionalElementsRepository())->getElements();
        $contents = $context->getContents();

        foreach ($elements as $element) {
            $contents = $this->removeElement($element, $contents);
        }

        return $context->setContents($contents);
    }

    /**
     * Remove an optional element.
     *
     * @param object $element
     * @param string $contents
     * @return string
     */
    protected function removeElement($element, $contents)
    {
        $newContents = preg_replace('@'.$element->regex.'@xi', '', $contents);

        if($newContents !== $contents && isset($element->elements)) {
            foreach ($element->elements as $element) {
                $newContents = $this->removeElement($element, $newContents);
            }
        }

        return $newContents;
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides()
    {
        return Options::OPTIONAL_ELEMENTS;
    }
}
