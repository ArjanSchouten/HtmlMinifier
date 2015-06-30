<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class Script implements PlaceholderInterface
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
        return preg_replace_callback('/<script\b[^>]*?>(((?!<\/script>).)*)<\/script>/is',
            function ($match) use ($placeholderContainer) {
                if (!empty($match[1])) {
                    return str_replace($match[1], $placeholderContainer->addPlaceholder($match[1]), $match[0]);
                }

                return $match[0];
            }, $contents);
    }
}