<?php

namespace Onimla\HTML\Traits;

trait MagicSetParent {

    public function __set($name, $value) {
        if (is_object($value) AND method_exists($value, 'setParent')) {
            $value->setParent($this);
        }

        parent::__set($name, $value);
    }
}
