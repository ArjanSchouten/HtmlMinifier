<?php

namespace ArjanSchouten\HTMLMin;

class Minify
{

    /**
     * All the available minifiers.
     *
     * @var array
     */
    protected $minifiers = [];

    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->minifiers = $rules;
    }

    /**
     * Run all minifiers over the provided contents.
     *
     * @param string $contents
     *
     * @return string
     */
    public function executeMinification($contents)
    {
        $placeholderContainer = new PlaceholderContainer();
        $contents = $this->setPlaceholders($contents, $placeholderContainer);
        $contents = $this->runMinifiers($contents);

        return $this->restorePlaceholdersContents($contents, $placeholderContainer);
    }

    /**
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function setPlaceholders($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($this->minifiers as $rule) {
            $contents = $rule->setPlaceholders($contents, $placeholderContainer);
        }

        return $contents;
    }

    /**
     * @param string $contents
     *
     * @return string
     */
    protected function runMinifiers($contents)
    {
        foreach ($this->minifiers as $rule) {
            $contents = $rule->minify($contents);
        }

        return $contents;
    }

    /**
     * @param string $contents
     * @param PlaceholderContainer $placeholderContainer
     *
     * @return string
     */
    protected function restorePlaceholdersContents($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach ($placeholderContainer as $placeholder => $original) {
            $contents = str_replace($placeholder, $original, $contents);
        }

        return $contents;
    }
}