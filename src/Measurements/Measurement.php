<?php

namespace ArjanSchouten\HtmlMinifier\Measurements;

class Measurement implements MeasurementInterface
{
    /**
     * @var \ArjanSchouten\HtmlMinifier\Measurements\ReferencePoint[]
     */
    private $referencePoints;

    /**
     * Create a measurement with the input before modification.
     *
     * @param string $input
     * @param string $keyName
     */
    public function __construct($input, $keyName = 'Initial')
    {
        $this->referencePoints[$keyName] = new ReferencePoint($keyName, mb_strlen($input, '8bit'));
    }

    /**
     * Add a step and measure the input size.
     *
     * @param string $input
     * @param string $keyname
     *
     * @return \ArjanSchouten\HtmlMinifier\Measurements\ReferencePoint[]
     */
    public function createReferencePoint($input, $keyname = null)
    {
        if ($keyname == null) {
            $keyname = 'Step: '.count($this->referencePoints) + 1;
        }

        $this->referencePoints[$keyname] = new ReferencePoint($keyname, mb_strlen($input, '8bit'));

        return $this->referencePoints;
    }

    /**
     * Get all the steps which are measured.
     *
     * @return \ArjanSchouten\HtmlMinifier\Measurements\ReferencePoint[]
     */
    public function getReferencePoints()
    {
        return $this->referencePoints;
    }
}
