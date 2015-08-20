<?php

namespace ArjanSchouten\HTMLMin\Repositories;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

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
     * @param string $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function loadJson($file)
    {
        $filesystem = new Filesystem();

        if ($filesystem->exists($file)) {
            $json = $filesystem->get($file);

            return json_decode($json);
        }

        throw new FileNotFoundException();
    }

    /**
     * Get the absolute path to the given resource file.
     *
     * @param  string $file
     * @return string
     */
    protected function resource($file)
    {
        return __DIR__.'/'.$this->resourceDir.'/'.$file;
    }
}