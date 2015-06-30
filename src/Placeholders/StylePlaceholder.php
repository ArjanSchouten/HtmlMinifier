<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class Style implements PlaceholderInterface
{

    /**
     * Replace critical content with a temp placeholder for integrity.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    public function setPlaceholders($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/<style\b[^>]*?>(((?!<\/style>).)*)<\/style>/is',
            function ($match) use ($placeholderContainer) {
                return str_replace($match[1], $placeholderContainer->addPlaceholder($match[1]), $match[0]);
            }, $contents);
    }
}