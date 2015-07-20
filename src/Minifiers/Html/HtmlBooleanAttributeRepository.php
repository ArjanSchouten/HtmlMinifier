<?php

namespace ArjanSchouten\HTMLMin\Minifiers\Html;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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
        if (self::$attributes === null) {
            self::$attributes = Collection::make($this->loadAttributes()->attributes);
        }

        return self::$attributes;
    }

    /**
     * Load the attributes from file.
     *
     * @param  null|string  $path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return array
     */
    protected function loadAttributes($path = null)
    {
        if ($path === null) {
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
