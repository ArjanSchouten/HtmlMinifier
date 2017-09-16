<?php

namespace ArjanSchouten\HtmlMinifier\Statistics;

use InvalidArgumentException;

class ReferencePoint
{
    private $name;

    private $bytes;

    /**
     * @param string $name
     * @param int $bytes
     */
    public function __construct($name, $bytes)
    {
        $this->name = $name;
        $this->bytes = $bytes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @return float
     */
    public function getKiloBytes()
    {
        return $this->bytes / 1000;
    }

    /**
     * @param int $bytes
     */
    public function addBytes($bytes)
    {
        if ($bytes < 0) {
            throw new InvalidArgumentException('The passed bytes amount is below zero: '.$bytes);
        }

        $this->bytes += $bytes;
    }
}
