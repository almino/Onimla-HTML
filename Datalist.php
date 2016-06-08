<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Datalist extends Element {

    public function __construct($id = FALSE, $children = FALSE, $attr = NULL) {
        if ($id === FALSE) {
            $this->uniqid();
        }
        parent::__construct('datalist', $attr, $children);
        $this->id($id);
        $this->selfClose(FALSE);
    }

    public function options($children) {
        require_once 'Option.class.php';
        foreach (self::arrayFlatten(func_get_args()) as $child) {
            if (is_string($child)) {
                $this->append(new Option($child));
            }
        }

        return $this;
    }

}
