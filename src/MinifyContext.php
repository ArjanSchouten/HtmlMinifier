<?php

namespace ArjanSchouten\HtmlMinifier;

class MinifyContext
{
    /**
     * @var \ArjanSchouten\HtmlMinifier\PlaceholderContainer
     */
    private $placeholderContainer;

    /**
     * @var string
     */
    private $contents;

    /**
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     */
    public function __construct(PlaceholderContainer $placeholderContainer)
    {
        $this->placeholderContainer = $placeholderContainer;
    }

    /**
     * Get the placeholdercontainer.
     *
     * @return \ArjanSchouten\HtmlMinifier\PlaceholderContainer
     */
    public function getPlaceholderContainer()
    {
        return $this->placeholderContainer;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
