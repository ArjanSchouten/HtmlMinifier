<?php
namespace ArjanSchouten\HTMLMin\Placeholders;

use ArjanSchouten\HTMLMin\PlaceholderContainer;

class Whitespace implements PlaceholderInterface
{

    protected $htmlPlaceholderTags = [
        'plaintext',
        'textarea',
        'listing',
        'code',
        'cite',
        'pre',
        'xmp',
    ];

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
        foreach ($this->htmlPlaceholderTags as $htmlTag) {
            $contents = $this->setHtmlTagPlaceholder($contents, $placeholderContainer, $htmlTag);
        }

        return $contents;
    }

    /**
     * Add placeholder for html tags with a placeholder to prevent data violation.
     *
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     * @param string $htmlTag
     *
     * @return string
     */
    protected function setHtmlTagPlaceholder($contents, PlaceholderContainer $placeholderContainer, $htmlTag)
    {
        $pattern = '/<' . $htmlTag . '(\b[^>]*?>)[\s\S]*?<\/' . $htmlTag . '>/i';

        return preg_replace_callback($pattern, function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }
}