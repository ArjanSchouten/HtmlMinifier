<?php
namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class CommentMinifier implements MinifierInterface
{

    /**
     * Replace remaining comments.
     *
     * @param string $contents
     *
     * @return string
     */
    public function minify($contents)
    {
        return preg_replace('/<!((?!>).)*>/s', '', $contents);
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