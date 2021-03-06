<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders;

interface PlaceholderInterface
{
    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($payload);
}
