<?php

namespace ArjanSchouten\HTMLMin\Placeholders\PHP;


use ArjanSchouten\HTMLMin\Placeholders\PlaceholderInterface;

class PHPPlaceholder implements PlaceholderInterface
{

    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($context)
    {
        return $context;
    }
}
