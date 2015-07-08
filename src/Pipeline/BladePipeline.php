<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;

class BladePipeline extends AbstractPipeline
{
    public function placeholders($options = [])
    {
        return parent::placeholders($options)
            ->add(new BladePlaceholder());
    }
}
