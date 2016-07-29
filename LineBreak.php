<?php

namespace Onimla\HTML;

class LineBreak extends Element {

    public function __construct($attr = FALSE) {
        parent::__construct('br', $attr);
        $this->selfClose(TRUE);
    }

}
