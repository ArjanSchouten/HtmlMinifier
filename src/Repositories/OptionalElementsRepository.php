<?php

namespace ArjanSchouten\HTMLMin\Repositories;

class OptionalElementsRepository extends AbstractRepository
{
    protected static $elements;

    /**
     * Get the optional html elments.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getElements()
    {
        if (self::$elements === null) {
            self::$elements = $this->loadJson($this->resource('OptionalElements.json'))->elements;
        }

        return self::$elements;
    }
}