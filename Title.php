<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Title extends Element {

    public function __construct($text = FALSE) {
        parent::__construct('title');
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
