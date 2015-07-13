<?php

namespace ArjanSchouten\HTMLMin;

use League\Pipeline\Pipeline;
use ArjanSchouten\HTMLMin\Pipeline\AbstractPipeline;

class Minify
{
    private $pipeline;

    public function buildPipeline(AbstractPipeline $pipeline, array $options)
    {
        $this->pipeline = (new Pipeline())
            ->pipe($pipeline->placeholders($options)->build())
            ->pipe($pipeline->minifiers($options)->build())
            ->pipe($pipeline->restores($options));

        return $this;
    }

    public function process(MinifyPipelineContext $context)
    {
        return $this->pipeline->process($context);
    }
}
