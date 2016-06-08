<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Fieldset extends Element {

    public function __construct($children = FALSE, $attr = NULL) {
        parent::__construct('fieldset', $attr, $children);
        $this->selfClose(FALSE);
    }

    public function disable() {
        return $this->attr('disabled', 'disabled');
    }

    public function enable() {
        return $this->removeAttr('disabled');
    }

    public function form($value = FALSE) {
        return $this->attr('form', $value, 'safe');
    }

    public function name($value = FALSE) {
        return $this->attr('name', $value, 'safe');
    }

}
