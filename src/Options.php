<?php

namespace ArjanSchouten\HtmlMinifier;

class Options
{
    const ALL = 'all';
    const EMPTY_ATTRIBUTES = 'remove-empty-attributes';
    const ATTRIBUTE_QUOTES = 'remove-attributequotes';
    const BOOLEAN_ATTRIBUTES = 'boolean-attributes';
    const OPTIONAL_ELEMENTS = 'optional-elements';
    const REMOVE_DEFAULTS = 'remove-defaults';
    const WHITESPACES = 'whitespaces';
    const COMMENTS = 'comments';

    private static $options;

    /**
     * Get all the options available for this minifier.
     *
     * @return array
     */
    public static function options()
    {
        if (self::$options === null) {
            self::$options = [
                self::WHITESPACES        => new Option(self::WHITESPACES, 'Remove redundant whitespaces', true),
                self::COMMENTS           => new Option(self::COMMENTS, 'Remove comments', true),
                self::BOOLEAN_ATTRIBUTES => new Option(self::BOOLEAN_ATTRIBUTES, 'Collapse boolean attributes from checked="checked" to checked', true),
                self::ATTRIBUTE_QUOTES   => new Option(self::ATTRIBUTE_QUOTES, 'Remove quotes around html attributes', false),
                self::OPTIONAL_ELEMENTS  => new Option(self::OPTIONAL_ELEMENTS, 'Remove optional elements which can be implied by the browser', false),
                self::REMOVE_DEFAULTS    => new Option(self::REMOVE_DEFAULTS, 'Remove defaults such as from <script type=text/javascript>', false),
                self::EMPTY_ATTRIBUTES   => new Option(self::EMPTY_ATTRIBUTES, 'Remove empty attributes. HTML boolean attributes are skipped', false),
            ];
        }

        return self::$options;
    }
}
