<?php

namespace Onimla\HTML;

class Fieldset extends Element {
    
    use Traits\Name;

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

}
