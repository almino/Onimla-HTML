<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

/**
 * Defines sample output from a computer program
 */
class Samp extends Element {

    public function __construct($text, $class = FALSE) {
        parent::__construct('samp');
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
