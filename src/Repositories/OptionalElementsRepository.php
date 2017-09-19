<?php

namespace ArjanSchouten\HtmlMinifier\Repositories;

class OptionalElementsRepository extends AbstractRepository
{
    protected static $elements;

    /**
     * Get the optional html elments.
     *
     * @throws \ArjanSchouten\HtmlMinifier\Exception\FileNotFoundException
     *
     * @return mixed
     */
    public function getElements()
    {
        if (self::$elements === null) {
            self::$elements = $this->loadJson($this->resource('OptionalElements.json'))->elements;
        }

        return self::$elements;
    }
}
