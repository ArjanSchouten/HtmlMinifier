<?php
namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Constants;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class WhitespaceMinifier implements MinifierInterface
{

    protected $maxHtmlLineLength = 32000;

    protected $minifyRules = [
        '\s?=\s?' => '=',
        '\s?\/>' => '>',
        '>\s<' => '><',
        '\s\s' => ' ',
        '\s?>\s?' => '>',
        '\s?<\s?' => '<',
        '\t' => ' ',
        '\r' => '',
        '\n' => '',
    ];

    /**
     * Execute the minification rules.
     *
     * @param string $contents
     *
     * @return string
     */
    public function process($context)
    {
        $context->setContents($this->trailingWhitespaces($context->getContents()));
        $context->setContents($this->runMinificationRules($context->getContents()));
        $context->setContents($this->removeSpacesAroundPlaceholders($context->getContents()));

        return $context->setContents($this->maxHtmlLineLength($context->getContents(), $this->maxHtmlLineLength));
    }

    /**
     * Remove trailing whitespaces around the contents.
     *
     * @param string $contents
     *
     * @return string
     */
    public function trailingWhitespaces($contents)
    {
        return trim($contents);
    }

    /**
     * Loop over the minification rules as long as changes in output occur.
     *
     * @param string $contents
     *
     * @return string
     */
    public function runMinificationRules($contents)
    {
        do {
            $originalContents = $contents;
            array_walk($this->minifyRules, function ($replace, $regex) use (&$contents) {
                $contents = preg_replace('/' . $regex . '/', $replace, $contents);
            });
        } while ($originalContents != $contents);

        return $contents;
    }

    /**
     * @param string $contents
     *
     * @return string
     */
    public function removeSpacesAroundPlaceholders($contents)
    {
        return preg_replace('/\s*(' . Constants::PLACEHOLDER_PATTERN . ')\s*/', '$1', $contents);
    }

    /**
     * Old browsers, firewalls and more can't handle to long lines.
     * Therefore add a linebreak after specified character length.
     *
     * @param int $maxHtmlLineLength
     * @param string $contents
     *
     * @return string
     */
    public function maxHtmlLineLength($contents, $maxHtmlLineLength)
    {
        $result = '';
        $splits = str_split($contents, $maxHtmlLineLength);
        foreach ($splits as $split) {
            $pos = strrpos($split, '><');
            if ($pos === false) {
                $result .= $split;
            } else {
                $result .= substr_replace($split, "\n", $pos + 1, 0);
            }
        }

        return $result;
    }

    /**
     * Indicates if minification rules depends on command options.
     *
     * @return string|bool if an option is needed, return the option name
     */
    public function provides()
    {
        return false;
    }
}
