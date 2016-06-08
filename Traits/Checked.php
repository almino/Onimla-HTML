<?php

namespace Onimla\HTML\Traits;

trait Checked {
    
    private $attr = 'checked';
    
    public function check() {
        $this->setChecked();
    }
    
    public function uncheck() {
        $this->unsetChecked();
    }

    public function checked() {
        $this->setChecked();
    }
    
    /**
     * 
     * @return bool
     */
    public function isChecked() {
        return $this->attr($this->attr);
    }

    public function setChecked() {
        $this->attr(new Attribute($this->attr));
    }

    public function unsetChecked() {
        $this->removeAttr($this->attr);
    }

}
