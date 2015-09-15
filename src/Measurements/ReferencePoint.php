<?php

namespace ArjanSchouten\HtmlMinifier\Measurements;

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
}