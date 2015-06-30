<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

interface PlaceholderInterface
{

    /**
     * Replace critical content with a temp placeholder for integrity.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    function setPlaceholders($contents, PlaceholderContainer $placeholderContainer);
}