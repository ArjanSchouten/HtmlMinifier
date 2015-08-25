<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\Options;
use ArjanSchouten\HTMLMin\Repositories\OptionalElementsRepository;

class OptionalElementMinifier implements MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
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

    protected function removeElement($element, $contents)
    {
        $newContents = preg_replace('@'.$element->regex.'@i', '', $contents);

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