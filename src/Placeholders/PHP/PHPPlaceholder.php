<?php

namespace ArjanSchouten\HTMLMin\Placeholders\PHP;

use ArjanSchouten\HTMLMin\Placeholders\PlaceholderInterface;

class PHPPlaceholder implements PlaceholderInterface
{

    /**
     * Replace PHP tags with a temporary placeholder.
     *
     * @param  \ArjanSchouten\HTMLMin\MinifyContext  $context
     * @return \ArjanSchouten\HTMLMin\MinifyContext
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
