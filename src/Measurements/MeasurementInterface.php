<?php

namespace ArjanSchouten\HtmlMinifier\Measurements;

interface MeasurementInterface
{
    /**
     * Add a step and measure the input size.
     *
     * @param string $input
     * @param string $keyname
     * @return array
     */
    function addStep($input, $keyname = null);

    /**
     * Get all the steps which are measured.
     *
     * @return array
     */
    function getSteps();
}