<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Caption extends Element {

    public function __construct($text, $class = FALSE) {
        parent::__construct('caption');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
