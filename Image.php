<?php

namespace Onimla\HTML;

use Onimla\HTML\Attribute\AlternateText;

class Image extends Element {

    function __construct($src = FALSE, $alt = FALSE) {
        parent::__construct('img');
        $this->src($src);
        $this->alt($alt);
    }

    function src($value = FALSE) {
        return $this->attr('src', $value);
    }

    function alt($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr('alt');
        }

        $this->attr(new AlternateText($value));

        return $this;
    }

}
