<?php

namespace ArjanSchouten\HtmlMinifier\Measurements;

class Measurement implements MeasurementInterface
{
    /**
     * @var array
     */
    private $steps;

    /**
     * Create a measurement with the input before modification.
     *
     * @param string $input
     * @param string $keyName
     */
    public function __construct($input, $keyName = 'Initial')
    {
        $this->steps[$keyName] = mb_strlen($input, '8bit');
    }

    /**
     * Add a step and measure the input size.
     *
     * @param string $input
     * @param string $keyname
     * @return array
     */
    public function addStep($input, $keyname = null)
    {
        if ($keyname == null) {
            $keyname = 'Step: '.count($this->steps) + 1;
        }

        $this->steps[$keyname] = mb_strlen($input, '8bit');

        return $this->steps;
    }

    /**
     * Get all the steps which are measured.
     *
     * @return array
     */
    public function getSteps()
    {
        return $this->steps;
    }
}
