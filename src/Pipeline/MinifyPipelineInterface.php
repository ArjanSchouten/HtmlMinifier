<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

interface MinifyPipelineInterface
{
    /**
     * Add placeholders to the pipeline based on provided options.
     *
     * @param  array  $options
     * @return \ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder
     */
    public function placeholders($options = []);

    /**
     * Add minifiers to the pipeline based on provided options.
     *
     * @param  array  $options
     * @return \ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder
     */
    public function minifiers($options = []);

    /**
     * Add restorers to the pipeline based on provided options.
     *
     * @param  array  $options
     * @return \ArjanSchouten\HTMLMin\Pipeline\PipelineBuilder
     */
    public function restores($options = []);
}
