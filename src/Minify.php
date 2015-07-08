<?php

namespace ArjanSchouten\HTMLMin;

use RuntimeException;
use League\Pipeline\CallableStage;
use ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Placeholders\CommentPlaceholder;
use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\Placeholders\WhitespacePlaceholder;
use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;

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
        if ($this->pipeline != null) {
            throw new RuntimeException('Pipeline is already build!');
        }

        $this->pipeline = (new PipelineBuilder())
            ->add(new CallableStage(function (MinifyPipelineContext $context) use ($options) {
                $placeholderPipeline = $this->buildPlaceholderPipeline($options);
                return $placeholderPipeline->process($context);
            }))
            ->add(new CallableStage(function (MinifyPipelineContext $context) use ($options) {
                $minifierPipeline = $this->buildMinifierPipeline($options);
                return $minifierPipeline->process($context);
            }))
            ->add(new CallableStage(function (MinifyPipelineContext $context) {
                return $context->setContents($context->getPlaceholderContainer()->restorePlaceholders($context->getContents()));
            }))
            ->build();
    }

    protected function buildPlaceholderPipeline()
    {
        return (new PipelineBuilder())
            ->add(new CommentPlaceholder())
            ->add(new WhitespacePlaceholder())
            ->build();
    }

    protected function buildMinifierPipeline($options)
    {
        return (new PipelineBuilder())
            ->add(new AttributeQuoteMinifier(), $options)
            ->add(new CommentMinifier(), $options)
            ->add(new EmptyAttributeMinifier(), $options)
            ->add(new JavascriptEventsMinifier(), $options)
            ->add(new RedundantAttributeMinifier(), $options)
            ->add(new WhitespaceMinifier(), $options)
            ->build();
    }

    public function process(MinifyPipelineContext $context)
    {
        return $this->pipeline->process($context);
    }
}
