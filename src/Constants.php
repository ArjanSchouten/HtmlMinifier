<?php

namespace ArjanSchouten\HtmlMinifier;

class Constants
{
    /**
     * Regexp for matching a placholder.
     */
    const PLACEHOLDER_PATTERN = '\[\[[a-zA-Z0-9]{32}[0-9]+\]\]';

    /**
     * Regexp for matching an attribute name.
     */
    const ATTRIBUTE_NAME_REGEX = '[a-zA-Z_:][-a-zA-Z0-9_:.]*';

    /**
     * @var string
     */
    public static $htmlEventNamePrefix = 'on';
}
