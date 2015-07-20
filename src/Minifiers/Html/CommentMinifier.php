<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Str;
use ArjanSchouten\HTMLMin\Minifiers\MinifierInterface;

class CommentMinifier implements MinifierInterface
{
    /**
     * Replace remaining comments.
     *
     * @param \ArjanSchouten\HTMLMin\MinifyPipelineContext  $context
     * @return \ArjanSchouten\HTMLMin\MinifyPipelineContext
     */
    public function process($context)
    {
        return $context->setContents(preg_replace_callback('/<!((?!>).)*>/s', function($match) {
            if (Str::contains(strtolower($match[0]), 'doctype')) {
                return $match[0];
            }

            return '';
        }, $context->getContents()));
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
