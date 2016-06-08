<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Span extends Element {

    public function __construct($text = FALSE, $class = FALSE) {
        parent::__construct('span');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
