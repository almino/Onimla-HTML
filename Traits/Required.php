<?php

namespace Onimla\HTML\Traits;

trait Required {
    
    private $attr = 'required';

    public function required() {
        $this->attr(new Attribute($this->attr));
    }

    public function setRequired() {
        $this->attr(new Attribute($this->attr));
    }

    public function unsetRequired() {
        $this->removeAttr($this->attr);
    }

}
