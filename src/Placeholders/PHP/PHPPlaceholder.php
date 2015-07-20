<?php

namespace ArjanSchouten\HTMLMin\Placeholders\PHP;


use ArjanSchouten\HTMLMin\Placeholders\PlaceholderInterface;

class PHPPlaceholder implements PlaceholderInterface
{

    /**
     * Process the payload.
     *
     * @param mixed $context
     * @return mixed
     */
    public function process($context)
    {
        $contents = $context->getContents();

        $contents = preg_replace_callback('/<\?=((?!\?>).)*\?>/s', function ($match) use ($context) {
            return $context->getPlaceholderContainer()->addPlaceholder($match[0]);
        }, $contents);

        $contents = preg_replace_callback('/<\?php((?!\?>).)*(\?>)?/s', function ($match) use ($context) {
            return $context->getPlaceholderContainer()->addPlaceholder($match[0]);
        }, $contents);

        return $context->setContents($contents);
    }
}
