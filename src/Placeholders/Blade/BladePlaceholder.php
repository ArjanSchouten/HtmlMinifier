<?php

namespace ArjanSchouten\HtmlMinifier\Placeholders\Blade;

use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Placeholders\PlaceholderInterface;

class BladePlaceholder implements PlaceholderInterface
{
    /**
     * @var array
     */
    private static $tags = [
        ['{{{', '}}}'],
        ['{!!', '!!}'],
        ['{--', '--}'],
        ['{{', '}}'],
    ];

    /**
     * Replace blade tags with a temporary placeholder.
     *
     * @param \ArjanSchouten\HtmlMinifier\MinifyContext $context
     * @return \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    public function process($context)
    {
        $contents = $this->setEchosPlaceholder($context->getContents(), $context->getPlaceholderContainer());

        $contents = $this->setBladeControlStructuresPlaceholder($contents, $context->getPlaceholderContainer());

        return $context->setContents($contents);
    }

    /**
     * Add placeholder for blade echo statements.
     *
     * @param string                                      $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @return string
     */
    protected function setEchosPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        foreach (self::$tags as $tag) {
            $pattern = sprintf(
                '/
                    @?              # Match a @ for blades ignore echo
                    %s\s*(.+?)\s*%s(\r?\n)?
                 /xs', $tag[0], $tag[1]);
            $contents = preg_replace_callback($pattern, function ($match) use ($placeholderContainer) {
                return $placeholderContainer->addPlaceholder($match[0]);
            }, $contents);
        }

        return $contents;
    }

    /**
     * Add placeholder for blade specific control structures.
     *
     * @param string                                      $contents
     * @param \ArjanSchouten\HtmlMinifier\PlaceholderContainer $placeholderContainer
     * @return string
     */
    protected function setBladeControlStructuresPlaceholder($contents, PlaceholderContainer $placeholderContainer)
    {
        return preg_replace_callback('/@\w*(\s*\(.*\))?/', function ($match) use ($placeholderContainer) {
            return $placeholderContainer->addPlaceholder($match[0]);
        }, $contents);
    }

    /**
     * Get blade tags.
     *
     * @return array
     */
    public static function getBladeTags()
    {
        return self::$tags;
    }

    /**
     * Set blade tags.
     *
     * @param array $tags
     */
    public static function setBladeTags(array $tags)
    {
        self::$tags = $tags;
    }
}
