<?php

namespace ArjanSchouten\HtmlMinifier\Repositories;

use Illuminate\Support\Collection;

class HtmlInlineElementsRepository extends AbstractRepository
{
    protected static $inlineElements;

    /**
     * Get inline Html Elements.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return \Illuminate\Support\Collection
     */
    public function getElements()
    {
        if (self::$inlineElements === null) {
            $elements = $this->loadJson($this->resource('HtmlInlineElements.json'))->elements;
            self::$inlineElements = Collection::make($elements);
        }

        return self::$inlineElements;
    }
}
