<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Heading extends Element {

    public function __construct($number, $text = FALSE, $class = FALSE) {
        if ($number < 1) {
            $number = 1;
        } elseif ($number > 6) {
            $number = 6;
        }

        parent::__construct('h' . $number);
        $this->addClass($class);
        $this->text($text);
        $this->selfClose(FALSE);
    }

}
