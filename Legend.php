<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Legend extends Element {

    public function __construct($text) {
        parent::__construct('legend');
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
