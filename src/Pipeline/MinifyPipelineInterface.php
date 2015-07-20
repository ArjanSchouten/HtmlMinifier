<?php
/**
 * Created by PhpStorm.
 * User: Arjan
 * Date: 20/07/2015
 * Time: 22:52
 */

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