<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Abbreviation extends Element {

    public function __construct($short, $full) {
        parent::__construct('abbr');
        $this->selfClose(FALSE);
        $this->attr('title', $full, 'encode');
        $this->text($short);
    }

}
