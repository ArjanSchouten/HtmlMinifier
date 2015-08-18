<?php

namespace ArjanSchouten\HTMLMin\Minifiers;

interface MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($payload);

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides();
}
