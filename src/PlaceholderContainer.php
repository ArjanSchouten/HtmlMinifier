<?php

namespace ArjanSchouten\HTMLMin;

use Illuminate\Support\Collection;

class PlaceholderContainer extends Collection
{
    /**
     * Hash used in placeholders.
     *
     * @var string
     */
    protected $replacementHash;

    public function __construct()
    {
        $replacementHashLimit = 32;
        $this->replacementHash = str_limit(md5(time()), $replacementHashLimit);
    }

    /**
     * Store a placeholder in the container.
     *
     * @param string $originalContent
     *
     * @return string $placeholder
     */
    public function addPlaceholder($originalContent)
    {
        $placeholder = $this->createUniquePlaceholder();
        $originalContent = $this->removeNestedPlaceholders($originalContent);
        $this->items[$placeholder] = $originalContent;

        return $placeholder;
    }

    /**
     * Create an unique placeholder.
     *
     * @return string
     */
    protected function createUniquePlaceholder()
    {
        return '[['.$this->replacementHash.$this->count().']]';
    }

    /**
     * Remove nested placeholders so no nested placholders remain in the original contents.
     *
     * @param string $originalContent
     *
     * @return string
     */
    protected function removeNestedPlaceholders($originalContent)
    {
        return preg_replace_callback('/'.Constants::PLACEHOLDER_PATTERN.'/', function ($match) {
            return $this->pull($match[0]);
        }, $originalContent);
    }
}
