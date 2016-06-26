<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Anchor extends Element {
    
    use Traits\Href;

    public function __construct($text = FALSE, $href = FALSE, $title = FALSE) {
        parent::__construct('a');
        $this->href($href);
        $this->title($title);
        $this->selfClose(FALSE);
        $this->text($text);
    }

}
