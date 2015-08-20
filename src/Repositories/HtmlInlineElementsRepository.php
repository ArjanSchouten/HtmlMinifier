<?php

namespace ArjanSchouten\HTMLMin\Repositories;

use Illuminate\Support\Collection;

class HtmlInlineElementsRepository extends AbstractRepository
{
    protected static $inlineElements;

    /**
     * Get inline Html Elements.
     *
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getElements()
    {
        if(self::$inlineElements === null) {
            $elements = $this->loadJson($this->resource('HtmlInlineElements.json'))->elements;
            self::$inlineElements = Collection::make($elements);
        }

        return self::$inlineElements;
    }
}