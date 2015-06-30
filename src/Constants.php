<?php
namespace ArjanSchouten\HTMLMin;

class Constants
{

    const PLACEHOLDER_PATTERN = '\[\[[a-zA-Z0-9]{32}[0-9]+\]\]';
    const ATTRIBUTE_NAME_REGEX = '[a-zA-Z_:][-a-zA-Z0-9_:.]*';
    public static $htmlEventNamePrefix = 'on';
}