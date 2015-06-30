<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class Comment implements PlaceholderInterface
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
        $contents = $this->setCDataPlaceholder($contents, $placeholderContainer);

        return $this->setConditionalCommentsPlaceholder($contents, $placeholderContainer);
    }

    protected function setCDataPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/<!\[CDATA\[((?!\]\]).)*\]\]/s', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }

    /**
     * Replace conditional placeholders used by IE.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setConditionalCommentsPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        $contents = preg_replace_callback('/<!--[\s]*\[([\s\S]*?)\][\s]*>/i',
            function ($match) use ($placeholderContainer) {
                return $placeholderContainer->addPlaceholder($match[0]);
            }, $contents);

        return preg_replace_callback('/<![\s]*\[([\s\S]*)\]-->/i', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }

}