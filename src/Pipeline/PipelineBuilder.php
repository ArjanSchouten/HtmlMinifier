<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use League\Pipeline\StageInterface;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use League\Pipeline\PipelineBuilder as LeaguePipelineBuilder;

class PipelineBuilder extends LeaguePipelineBuilder
{
    public function add(StageInterface $stage, $options = null)
    {
        if ($options == null) {
            return parent::add($stage);
        }

        if ($stage instanceof MinifierInterface && $stage->provides()) {
            if (isset($options[$stage->provides()]) && $options[$stage->provides()]) {
                parent::add($stage);
            }
        } else {
            parent::add($stage);
        }

        return $this;
    }
}
