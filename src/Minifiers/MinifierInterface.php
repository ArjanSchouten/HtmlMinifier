<?php

namespace ArjanSchouten\HtmlMinifier\Minifiers;

use ArjanSchouten\HtmlMinifier\MinifyContext;

interface MinifierInterface
{
    /**
     * Process the payload.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process(MinifyContext $context);

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string
     */
    public function provides();
}
