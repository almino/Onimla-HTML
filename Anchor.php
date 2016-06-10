<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Anchor extends Element {

    public function __construct($text = FALSE, $href = FALSE, $title = FALSE) {
        parent::__construct('a');
        $this->href($href);
        $this->title($title);
        $this->selfClose(FALSE);
        $this->text($text);
    }

    public function href($url = FALSE) {
        return $this->attr(__FUNCTION__, $url);
    }

}
