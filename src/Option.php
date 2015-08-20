<?php

namespace ArjanSchouten\HTMLMin;


class Option
{
    private $name;

    private $description;

    private $default = true;

    public function __construct($name, $description, $default = true)
    {
        $this->name = $name;
        $this->description = $description;
        $this->default = $default;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }
}