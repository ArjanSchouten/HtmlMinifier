<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class JavascriptEvents implements PlaceholderInterface
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
        return $this->setJavascriptEventPlaceholder($contents, $placeholderContainer);
    }

    /**
     * Replace contents of html event attributes, e.g. onclick, with a placeholder.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setJavascriptEventPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/\s' . Constants::$htmlEventNamePrefix . '\S+=[\'"]?(javascript:)?((?!").*)"/',
            function ($match) use ($placeholderContainer) {
                return str_replace($match[2], $placeholderContainer->addPlaceholder($match[2]), $match[0]);
            }, $contents);
    }
}