<?php

namespace Onimla\HTML;

class Checkbox extends Input {

    use Traits\Checked;

    public function __construct($name, $value = FALSE, $attr = FALSE) {
        parent::__construct($name, $value, 'checkbox', $attr);
    }

}
