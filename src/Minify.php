<?php

namespace ArjanSchouten\HTMLMin;

use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder;
use ArjanSchouten\HTMLMin\Placeholders\CommentPlaceholder;
use League\Pipeline\CallableStage;
use RuntimeException;

class Minify
{
    /**
     * Minification pipeline.
     *
     * @var \League\Pipeline\Pipeline
     */
    protected $pipeline;

    public function buildPipeline($options)
    {
        if ($this->pipeline != null)
            throw new RuntimeException('Pipeline is already build!');

        $this->pipeline = (new PipelineBuilder())
            ->add(new CallableStage(function (MinifyPipelineContext $context) use ($options) {
                $placeholderPipeline = $this->buildPlaceholderPipeline($options);
                return $placeholderPipeline->process($context);
            }))
            ->add(new CallableStage(function (MinifyPipelineContext $context) use ($options) {
                $minifierPipeline = $this->buildMinifierPipeline($options);
                return $minifierPipeline->process($context);
            }))
            ->add(new CallableStage(function (MinifyPipelineContext $context){
                return $context->getPlaceholderContainer()->restorePlaceholders($context->getContents());
            }))
            ->build();
    }

    protected function buildPlaceholderPipeline($options)
    {
        return (new PipelineBuilder())
            ->add(new CommentPlaceholder, $options)
            ->add(new CommentMinifier, $options)
            ->add(new EmptyAttributeMinifier, $options)
            ->add(new JavascriptEventsMinifier, $options)
            ->add(new RedundantAttributeMinifier, $options)
            ->add(new WhitespaceMinifier, $options)
            ->build();
    }

    protected function buildMinifierPipeline()
    {
        return (new PipelineBuilder())
            ->add(new AttributeQuoteMinifier, $options)
            ->add(new CommentMinifier, $options)
            ->add(new EmptyAttributeMinifier, $options)
            ->add(new JavascriptEventsMinifier, $options)
            ->add(new RedundantAttributeMinifier, $options)
            ->add(new WhitespaceMinifier, $options)
            ->build();
    }

    /**
     * Run all minifiers over the provided contents.
     *
     * @param string $contents
     *
     * @return string
     */
    public function executeMinification($contents)
    {
        $placeholderContainer = new PlaceholderContainer();
        $contents = $this->setPlaceholders($contents, $placeholderContainer);
        $contents = $this->runMinifiers($contents);

        return $this->restorePlaceholdersContents($contents, $placeholderContainer);
    }

    /**
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setPlaceholders($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($this->minifiers as $rule) {
            $contents = $rule->setPlaceholders($contents, $placeholderContainer);
        }

        return $contents;
    }

    /**
     * @param string $contents
     *
     * @return string
     */
    protected function runMinifiers($contents)
    {
        foreach ($this->minifiers as $rule) {
            $contents = $rule->minify($contents);
        }

        return $contents;
    }

    /**
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function restorePlaceholdersContents($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($placeholderContainer as $placeholder => $original) {
            $contents = str_replace($placeholder, $original, $contents);
        }

        return $contents;
    }
}
