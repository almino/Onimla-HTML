<?php

namespace Onimla\HTML;

class Paragraph extends Element {

    public function __construct($text = FALSE, $class = FALSE) {
        parent::__construct('p');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
