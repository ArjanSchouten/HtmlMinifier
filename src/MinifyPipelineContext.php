<?php

namespace ArjanSchouten\HTMLMin;

class MinifyPipelineContext
{

    /**
     * @var \ArjanSchouten\HTMLMin\PlaceholderContainer
     */
    private $placeholderContainer;

    /**
     * @var string
     */
    private $contents;

    /**
     * @param \ArjanSchouten\HTMLMin\PlaceholderContainer $placeholderContainer
     * @return self
     */
    public function __construct(PlaceholderContainer $placeholderContainer)
    {
        $this->placeholderContainer = $placeholderContainer;
    }

    /**
     * @return \ArjanSchouten\HTMLMin\PlaceholderContainer
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
     * @param string  $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}