<?php

namespace ArjanSchouten\HtmlMinifier\Measurements;

interface MeasurementInterface
{
    /**
     * Add a step and measure the input size.
     *
     * @param string $inputSize
     * @param string $keyname
     *
     * @return array
     */
    public function createReferencePoint($inputSize, $keyname = null);

    /**
     * Get all the steps which are measured.
     *
     * @return array
     */
    public function getReferencePoints();
}
