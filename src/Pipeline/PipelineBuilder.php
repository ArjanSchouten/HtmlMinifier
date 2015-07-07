<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use League\Pipeline\PipelineBuilder as LeaguePipelineBuilder;

class PipelineBuilder extends LeaguePipelineBuilder
{

    public function add(MinifierInterface $stage, $options = null)
    {
        if ($options == null) {
            return parent::add($stage);
        }

        if ($stage->provides()) {
            if (isset($options[$stage->provides()]) && $options[$stage->provides()]) {
                parent::add($stage);
            }
        }
        return $this;
    }
}