<?php

namespace ArjanSchouten\HtmlMinifier;

use ArjanSchouten\HtmlMinifier\Statistics\Statistics;
use ArjanSchouten\HtmlMinifier\Statistics\StatisticsInterface;

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
     * @var \ArjanSchouten\HtmlMinifier\Statistics\StatisticsInterface
     */
    private $statistics;

    /**
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @param \ArjanSchouten\HtmlMinifier\Statistics\StatisticsInterface|null $statistics
     */
    public function __construct(PlaceholderContainer $placeholderContainer, StatisticsInterface $statistics = null)
    {
        $this->placeholderContainer = $placeholderContainer;
        $this->statistics = $statistics;
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

    /**
     * Add a minification step.
     *
     * @param string $input
     * @param string $keyName
     */
    public function addMinificationStep($input, $keyName = null)
    {
        if ($this->statistics === null) {
            $this->statistics = new Statistics($input, $keyName);

            return;
        }

        $this->statistics->createReferencePoint($this->placeholderContainer->getContentSize($input), $keyName);
    }

    /**
     * Get the minification statistics.
     *
     * @return \ArjanSchouten\HtmlMinifier\Statistics\StatisticsInterface
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}
