<?php

namespace Onimla\HTML;

use Onimla\HTML\Element;

class UnorderedList extends Element {

    public function __construct($children = FALSE) {
        parent::__construct('ul', FALSE, func_get_args());
    }

}
