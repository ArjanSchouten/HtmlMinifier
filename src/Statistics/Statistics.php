<?php

namespace ArjanSchouten\HtmlMinifier\Statistics;

class Statistics implements StatisticsInterface
{
    /**
     * @var \ArjanSchouten\HtmlMinifier\Statistics\ReferencePoint[]
     */
    private $referencePoints;

    /**
     * Create statistics with the input before modification.
     *
     * @param string $input
     * @param string $keyName
     */
    public function __construct($input, $keyName = 'Original input')
    {
        $this->referencePoints[$keyName] = new ReferencePoint($keyName, mb_strlen($input, '8bit'));
    }

    /**
     * Add a step and measure the input size.
     *
     * @param int $inputSize
     * @param string $keyname
     *
     * @return \ArjanSchouten\HtmlMinifier\Statistics\ReferencePoint[]
     */
    public function createReferencePoint($inputSize, $keyname = null)
    {
        if ($keyname === null) {
            $keyname = 'Step: '.(count($this->referencePoints) + 1);
        }

        if (!array_key_exists($keyname, $this->referencePoints)) {
            $this->referencePoints[$keyname] = new ReferencePoint($keyname, $inputSize);
        } else {
            $this->referencePoints[$keyname]->addBytes($inputSize);
        }

        return $this->referencePoints;
    }

    /**
     * Get all the steps which are measured.
     *
     * @return \ArjanSchouten\HtmlMinifier\Statistics\ReferencePoint[]
     */
    public function getReferencePoints()
    {
        return $this->referencePoints;
    }

    /**
     * Get the total saved bytes in bytes.
     *
     * @return int
     */
    public function getTotalSavedBytes()
    {
        $initialStep = array_first($this->referencePoints);
        $lastStep = array_last($this->referencePoints);

        return $initialStep->getBytes() - $lastStep->getBytes();
    }
}
