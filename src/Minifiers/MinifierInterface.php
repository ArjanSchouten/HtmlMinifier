<?php

namespace ArjanSchouten\HTMLMin\Minifiers;

use League\Pipeline\StageInterface;

interface MinifierInterface extends StageInterface
{

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    function provides();
}