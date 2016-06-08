<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class ListItem extends Element {
    public function __construct($children = FALSE, $attr = NULL) {
        parent::__construct('li', $attr, $children);
    }
}
