<?php

namespace Onimla\HTML;

#require_once 'Input.class.php';

class Search extends Input {
    public function __construct($name, $value = FALSE, $attr = NULL) {
        parent::__construct($name, 'search', $value, $attr);
    }
}
