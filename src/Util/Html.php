<?php

namespace ArjanSchouten\HtmlMinifier\Util;

use Illuminate\Support\Str;

class Html
{
    /**
     * Check if an attribute has surrounding attributes.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public static function hasSurroundingAttributes($attribute)
    {
        return Str::startsWith($attribute, ' ') && Str::endsWith($attribute, ' ');
    }

    /**
     * Check if an attribute is a HTML 5 data-* attribute.
     *
     * @param string $attribute
     *
     * @return int
     */
    public static function isDataAttribute($attribute)
    {
        return preg_match('/data-/', $attribute);
    }

    /**
     * Check if an attribute is the last attribute of the element.
     *
     * @param  $attribute
     *
     * @return bool
     */
    public static function isLastAttribute($attribute)
    {
        return !Str::endsWith($attribute, ' ');
    }
}
