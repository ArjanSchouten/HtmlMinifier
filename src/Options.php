<?php

namespace ArjanSchouten\HTMLMin;

class Options
{
    const ALL = 'all';
    const ATTRIBUTE_QUOTES = 'remove-attributequotes';
    const EMPTY_ATTRIBUTES = 'remove-empty-attributes';
    const REMOVE_DEFAULTS = 'remove-defaults';
    const WHITESPACES = 'whitespaces';
    const COMMENTS = 'comments';

    private static $options;

    public static function options()
    {
        if (self::$options === null) {
            self::$options = [
                Options::WHITESPACES        => new Option(Options::WHITESPACES, 'Remove redundant spaces', true),
                Options::COMMENTS           => new Option(Options::COMMENTS, 'Remove comments', true),
                Options::ATTRIBUTE_QUOTES   => new Option(Options::ATTRIBUTE_QUOTES, 'Remove quotes around html attributes', false),
                Options::REMOVE_DEFAULTS    => new Option(Options::REMOVE_DEFAULTS, 'Remove defaults such as from <script type=text/javascript>', false),
                Options::EMPTY_ATTRIBUTES   => new Option(Options::EMPTY_ATTRIBUTES, 'Remove empty attributes. HTML boolean attributes are skipped', false)
            ];
        }

        return self::$options;
    }
}
