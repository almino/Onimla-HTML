<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

/**
 * @method string|FALSE|self for(string $value = FALSE)
 */
class Label extends Element {

    public function __construct($for = FALSE, $text = FALSE) {
        parent::__construct('label');
        $this->selfClose(FALSE);
        $this->attrFor($for);
        $this->text($text);
    }

    public function __call($name, $arguments) {
        if ($name == 'for') {
            return call_user_func_array(array($this, 'attrFor'), $arguments);
        }

        # Coloca o nome no começo do array
        array_unshift($arguments, $name);
        return call_user_func_array(array($this, 'attr'), $arguments);
    }

    public function attrFor($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr('for');
        }

        if (is_object($value) AND method_exists($value, 'id')) {
            if (strlen($value->id()) < 1) {
                if (method_exists($value, 'uniqid')) {
                    $value->uniqid();
                } else {
                    $value->id(uniqid());
                }
            }
            $this->attr('for', $value->id(), 'safe');
        } else {
            $this->attr('for', $value, 'safe');
        }

        return $this;
    }
    
    public function path() {
        return parent::path() . "[{$this->getAttribute('for')}]";
    }

}
