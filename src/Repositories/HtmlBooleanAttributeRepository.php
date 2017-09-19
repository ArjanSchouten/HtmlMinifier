<?php

namespace ArjanSchouten\HtmlMinifier\Repositories;

class HtmlBooleanAttributeRepository extends AbstractRepository
{
    protected static $attributes;

    /**
     * Get the html boolean attributes.
     *
     * @throws \ArjanSchouten\HtmlMinifier\Exception\FileNotFoundException
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAttributes()
    {
        if (self::$attributes === null) {
            self::$attributes = $this->loadJson($this->resource('HtmlBooleanAttributes.json'))->attributes;
        }

        return self::$attributes;
    }
}
