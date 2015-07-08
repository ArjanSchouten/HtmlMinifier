<?php

namespace ArjanSchouten\HTMLMin;

use ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder;

class PipelineFactory
{
    private $pipelineBuilder;

    private $stages = [];

    public function __construct(PipelineBuilder $pipelineBuilder)
    {
        $this->pipelineBuilder = $pipelineBuilder;
    }

    public function add($stage)
    {
        if ($stage instanceof PipelineBuilder) {
            $this->stages[] = $stage;
        } else {
            $this->pipelineBuilder->add($stage);
        }

        return $this;
    }

    public function build()
    {
        foreach ($this->stages as $stage) {
            $this->pipelineBuilder->add($stage->build());
        }

        return $this->pipelineBuilder->build();
    }
}