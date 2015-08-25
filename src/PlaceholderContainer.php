<?php

namespace ArjanSchouten\HtmlMinifier;

use Illuminate\Support\Collection;

class PlaceholderContainer extends Collection
{
    /**
     * Hash used in placeholders.
     *
     * @var string
     */
    protected $replacementHash;

    /**
     * Create a new placeholdercontainer.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $replacementHashLimit = 32;
        $this->replacementHash = str_limit(md5(time()), $replacementHashLimit);
        parent::__construct($items);
    }

    /**
     * @param string $contents
     * @return string
     */
    public function restorePlaceholders($contents)
    {
        foreach ($this->all() as $placeholder => $original) {
            $contents = str_replace($placeholder, $original, $contents);
        }

        return $contents;
    }

    /**
     * Store a placeholder in the container.
     *
     * @param string $originalContent
     * @return string $placeholder
     */
    public function addPlaceholder($originalContent)
    {
        if (($key = array_search($originalContent, $this->all()))) {
            $placeholder = $key;
        } else {
            $placeholder = $this->createUniquePlaceholder();
        }

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
     * @return string
     */
    protected function removeNestedPlaceholders($originalContent)
    {
        return preg_replace_callback('/'.Constants::PLACEHOLDER_PATTERN.'/', function ($match) {
            return $this->get($match[0]);
        }, $originalContent);
    }
}
