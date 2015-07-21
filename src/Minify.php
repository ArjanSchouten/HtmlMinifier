<?php

namespace ArjanSchouten\HTMLMin;

use League\Pipeline\Pipeline;
use ArjanSchouten\HTMLMin\Pipeline\BasePipeline;

class Minify
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * Build the complete minification pipeline.
     *
     * @param  \ArjanSchouten\HTMLMin\Pipeline\BasePipeline  $pipeline
     * @param  array  $options
     * @return $this
     */
    public function buildPipeline(BasePipeline $pipeline, array $options)
    {
        $this->pipeline = (new Pipeline())
            ->pipe($pipeline->placeholders($options)->build())
            ->pipe($pipeline->minifiers($options)->build())
            ->pipe($pipeline->restores($options)->build());

        return $this;
    }

    /**
     * Execute the pipeline to minify views.
     *
     * @param  \ArjanSchouten\HTMLMin\MinifyPipelineContext  $context
     * @return  string
     */
    public function process(MinifyPipelineContext $context)
    {
        return $this->pipeline->process($context);
    }
}
