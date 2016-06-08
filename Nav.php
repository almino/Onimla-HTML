<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Nav extends Element {
    public function __construct($children = FALSE, $attr = NULL) {
        parent::__construct('nav', $attr, $children);
    }
}
