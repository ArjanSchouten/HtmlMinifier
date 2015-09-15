<?php

namespace ArjanSchouten\HtmlMinifier;

use ArjanSchouten\HtmlMinifier\Measurements\Measurement;
use ArjanSchouten\HtmlMinifier\Measurements\MeasurementInterface;

class MinifyContext
{
    /**
     * @var \ArjanSchouten\HtmlMinifier\PlaceholderContainer
     */
    private $placeholderContainer;

    /**
     * @var string
     */
    private $contents;

    /**
     * @var \ArjanSchouten\HtmlMinifier\Measurements\MeasurementInterface
     */
    private $measurement;

    /**
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     */
    public function __construct(PlaceholderContainer $placeholderContainer, MeasurementInterface $measurement = null)
    {
        $this->placeholderContainer = $placeholderContainer;
        $this->measurement = $measurement;
    }

    /**
     * Get the placeholdercontainer.
     *
     * @return \ArjanSchouten\HtmlMinifier\PlaceholderContainer
     */
    public function getPlaceholderContainer()
    {
        return $this->placeholderContainer;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Add a measurement step.
     *
     * @param string $input
     * @param string $keyName
     */
    public function addMeasurementStep($input, $keyName = null)
    {
        if ($this->measurement === null) {
            $this->measurement = new Measurement($input, $keyName);

            return;
        }

        $this->measurement->createReferencePoint($input, $keyName);
    }

    /**
     * Get the measurement.
     *
     * @return \ArjanSchouten\HtmlMinifier\Measurements\MeasurementInterface
     */
    public function getMeasurement()
    {
        return $this->measurement;
    }
}
