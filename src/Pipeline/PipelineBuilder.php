<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use League\Pipeline\StageInterface;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;
use League\Pipeline\PipelineBuilder as LeaguePipelineBuilder;

class PipelineBuilder extends LeaguePipelineBuilder
{
    /**
     * Add stage to the pipelinebuilder if there are no restrictions.
     *
     * @param  \League\Pipeline\StageInterface  $stage
     * @param  array|null  $options
     * @return $this
     */
    public function add(StageInterface $stage, array $options = null)
    {
        if ($options === null) {
            return parent::add($stage);
        } elseif (array_key_exists('all', $options) && $options['all']) {
            parent::add($stage);
        }

        if ($stage instanceof MinifierInterface) {
            if ($this->isEnabledMinifier($stage, $options)) {
                parent::add($stage);
            }
        }

        return $this;
    }

    /**
     * Check if the minifier is enabled based on provided restrictions.
     *
     * @param  \ArjanSchouten\HTMLMin\Minifiers\MinifierInterface  $stage
     * @param  array  $options
     * @return bool
     */
    protected function isEnabledMinifier(MinifierInterface $stage, array $options)
    {
        return !$stage->provides() || (isset($options[$stage->provides()]) && $options[$stage->provides()]);
    }
}
