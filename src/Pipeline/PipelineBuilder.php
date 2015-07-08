<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use League\Pipeline\StageInterface;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use League\Pipeline\PipelineBuilder as LeaguePipelineBuilder;

class PipelineBuilder extends LeaguePipelineBuilder
{
    public function add(StageInterface $stage, array $options = null)
    {
        if ($options == null) {
            return parent::add($stage);
        } elseif (array_key_exists('all', $options) && $options['all']) {
            parent::add($stage);
        }

        if ($stage instanceof MinifierInterface && $stage->provides()) {
            if ($this->isEnabledMinifier($stage, $options)) {
                parent::add($stage);
            }
        }

        return $this;
    }

    protected function isEnabledMinifier(StageInterface $stage, array $options)
    {
        return !$stage->provides() || (isset($options[$stage->provides()]) && $options[$stage->provides()]);
    }
}
