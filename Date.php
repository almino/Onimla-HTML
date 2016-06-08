<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Date extends Input {
    public function __construct($name, $value = FALSE, $attr = NULL) {
        parent::__construct($name, 'date', $value, $attr);
    }
}
