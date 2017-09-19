<?php

namespace ArjanSchouten\HtmlMinifier\Repositories;

use ArjanSchouten\HtmlMinifier\Exception\FileNotFoundException;

abstract class AbstractRepository
{
    /**
     * The resource directory name.
     *
     * @var string
     */
    protected $resourceDir = 'resources';

    /**
     * Load a json file and decode it.
     *
     * @param string $path
     *
     * @throws \ArjanSchouten\HtmlMinifier\Exception\FileNotFoundException
     *
     * @return mixed
     */
    protected function loadJson($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException();
        }

        return json_decode(file_get_contents($path));
    }

    /**
     * Get the absolute path to the given resource file.
     *
     * @param string $file
     *
     * @return string
     */
    protected function resource($file)
    {
        return __DIR__.'/'.$this->resourceDir.'/'.$file;
    }
}
