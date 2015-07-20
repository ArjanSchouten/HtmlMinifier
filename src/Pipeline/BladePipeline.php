<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;

class BladePipeline extends AbstractPipeline
{
    /**
     * Add placeholders to the pipeline based on provided options.
     *
     * @param  array  $options
     * @return \ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder
     */
    public function placeholders($options = [])
    {
        return parent::placeholders($options)
            ->add(new BladePlaceholder());
    }
}
