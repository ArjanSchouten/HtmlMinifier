<?php

namespace ArjanSchouten\HtmlMinifier\Repositories;

use Illuminate\Support\Collection;

class HtmlBooleanAttributeRepository extends AbstractRepository
{
    protected static $attributes;

    /**
     * Get the html boolean attributes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAttributes()
    {
        if (self::$attributes === null) {
            $attributes = $this->loadJson($this->resource('HtmlBooleanAttributes.json'))->attributes;
            self::$attributes = Collection::make($attributes);
        }

        return self::$attributes;
    }
}
