<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Strong extends Element {

    public function __construct($text = FALSE, $class = FALSE) {
        parent::__construct('strong');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
