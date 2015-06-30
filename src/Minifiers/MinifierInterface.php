<?php

namespace ArjanSchouten\HTMLMin\Minifiers;

interface MinifierInterface
{

    /**
     * Execute the minification rule.
     *
     * @param string $contents
     *
     * @return string
     */
    function minify($contents);

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    function provides();
}