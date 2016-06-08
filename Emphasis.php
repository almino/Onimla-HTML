<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Emphasis extends Element {

    public function __construct($text, $class = FALSE) {
        parent::__construct('em');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
