<?php

namespace Onimla\HTML\Traits;

trait ReadOnly {
    
    private $attr = 'readonly';

    public function readOnly() {
        $this->attr(new Attribute($this->attr));
    }

    public function setReadOnly() {
        $this->attr(new Attribute($this->attr));
    }

    public function unsetReadOnly() {
        $this->removeAttr($this->attr);
    }

}
