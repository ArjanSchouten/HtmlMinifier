<?php

namespace ArjanSchouten\HTMLMin\Minifiers;

use ArjanSchouten\HTMLMin\MinifyContext;

interface MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyContext $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
     */
    public function process(MinifyContext $context);

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides();
}
