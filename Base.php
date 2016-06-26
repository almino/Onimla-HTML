<?php

namespace Onimla\HTML;

class Base extends Element {

    use Traits\Href;

    public function __construct($href = FALSE) {
        parent::__construct('base');
        $this->href($href);
    }

}
