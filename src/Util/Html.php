<?php

namespace ArjanSchouten\HTMLMin\Util;

use Illuminate\Support\Str;

class Html
{

    public static function hasSurroundingAttributes($attribute)
    {
        return Str::startsWith($attribute, ' ') && Str::endsWith($attribute, ' ');
    }

    public static function isDataAttribute($attribute)
    {
        return preg_match('/data-/', $attribute);
    }

    public static function isLastAttribute($attribute)
    {
        return !Str::endsWith($attribute, ' ');
    }
}