<?php

namespace ArjanSchouten\HTMLMin\Minifiers;

interface MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param mixed $payload
     * @return mixed
     */
    public function process($payload);

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides();
}
