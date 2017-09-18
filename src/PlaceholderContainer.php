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
     *
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
     * Replace ```$value``` with a placeholder and store it in the container.
     *
     * @param string $value
     * @param string $content
     *
     * @return string $contents
     */
    public function addPlaceholder($value, $content)
    {
        $placeholder = $this->createPlaceholder($value);

        return str_replace($value, $placeholder, $content);
    }

    /**
     * Create a unique placeholder for the given contents.
     *
     * @param string $value
     *
     * @return string $placeholder
     */
    public function createPlaceholder($value)
    {
        if (($key = array_search($value, $this->all()))) {
            $placeholder = $key;
        } else {
            $placeholder = $this->createUniquePlaceholder();
        }

        $value = $this->removeNestedPlaceholders($value);
        $this->items[$placeholder] = $value;

        return $placeholder;
    }

    /**
     * Calculate the byte size of the placeholders.
     *
     * @param string $contentsWithPlaceholders
     * @return int
     */
    public function getContentSize($contentsWithPlaceholders)
    {
        $placeholderSize = $this->map(function ($value, $key) use (&$contentsWithPlaceholders){
            $count = substr_count($contentsWithPlaceholders, $key);

            return strlen($key) * $count - strlen($value) * $count;
        })->sum();

        return strlen($contentsWithPlaceholders) - $placeholderSize;
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
