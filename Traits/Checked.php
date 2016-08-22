<?php

namespace Onimla\HTML\Traits;

use Onimla\HTML\Constant;

trait Checked {

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
     * @return boolean
     */
    public function isChecked() {
        return (bool) $this->attr(Constant::CHECKED);
    }

    public function setChecked() {
        $this->attr(new Attribute(Constant::CHECKED));
    }

    public function unsetChecked() {
        $this->removeAttr(Constant::CHECKED);
    }

}
