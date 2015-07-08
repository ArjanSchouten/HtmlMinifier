<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class HtmlBooleanAttributeRepository
{
    private static $attributes;

    /**
     * Get the html boolean attributes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAttributes()
    {
        if (static::$attributes == null) {
            static::$attributes = Collection::make($this->loadAttributes()->attributes);
        }

        return static::$attributes;
    }

    /**
     * Load the attributes from file.
     *
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return array
     */
    protected function loadAttributes($path = null)
    {
        if (!$path) {
            $path = __DIR__.'/HtmlBooleanAttributes.json';
        }

        $filesystem = new Filesystem();

        if ($filesystem->exists($path)) {
            $json = $filesystem->get($path);

            return json_decode($json);
        }

        throw new FileNotFoundException();
    }
}
