<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Image extends Element {

    function __construct($src, $alt = FALSE) {
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

        $this->attr(new Attribute\AlternateText($value));

        return $this;
    }

}
