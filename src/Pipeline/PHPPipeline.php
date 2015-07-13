<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use ArjanSchouten\HTMLMin\Placeholders\PHP\PHPPlaceholder;

class PHPPipeline extends AbstractPipeline
{
    public function placeholders($options = [])
    {
        return parent::placeholders($options)
            ->add(new PHPPlaceholder());
    }
}