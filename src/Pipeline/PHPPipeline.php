<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use ArjanSchouten\HTMLMin\Placeholders\PHP\PHPPlaceholder;

class PHPPipeline extends BasePipeline
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
            ->add(new PHPPlaceholder());
    }
}
