<?php

namespace Onimla\HTML;

/*
require_once 'Element.class.php';
require_once 'Option.class.php';
 */

class Select extends Element {

    /**
     * @param string $name
     * @param array|Element $options
     */
    public function __construct($name, $options = FALSE) {
        parent::__construct('select');
        $this->name($name);
        $this->selfClose(FALSE);
        if ($options !== FALSE) {
            $this->append($options);
        }
    }

    public function autofocus($set = TRUE) {
        return $set ? $this->attr('autofocus', 'autofocus') : $this->removeAttr('autofocus');
    }

    public function enable() {
        return $this->removeAttr('disabled');
    }

    public function enabled() {
        return !$this->attr('disabled');
    }

    public function disable() {
        return $this->attr('disabled', 'disabled');
    }

    public function disabled() {
        return (bool) $this->attr('disabled');
    }

    public function form($value = FALSE) {
        return $this->attr('form', $value, 'safe');
    }

    public function multiple($set = TRUE) {
        return $set ? $this->attr('multiple', 'multiple') : $this->removeAttr('multiple');
    }

    public function name($value = FALSE) {
        return $this->attr('name', $value, 'safe');
    }

    public function required($set = TRUE) {
        return $set ? $this->attr('required', 'required') : $this->removeAttr('required');
    }

    public function size($number = FALSE) {
        return $this->attr('size', $number);
    }

    public function deselectAll() {
        foreach ($this->children as $child) {
            if ($child instanceof Element) {
                $child->removeAttr('selected');
            }
        }

        return $this;
    }
    
    public function value($value = FALSE) {
        $found = $this->findByAttr('selected', 'selected');
        
        if (is_object($found) AND method_exists($found, 'attr')) {
            return $found->attr('value') === FALSE ? $found->text() : $found->attr('value');
        }
        
    }

    public function firstOption($text = FALSE) {
        if ($text === FALSE) {
            return $this->first();
        }

        if (!$text instanceof Element) {
            $option = new Option(0);
            $option->text($text)->select()->disable();
            $text = $option;
        }

        return $this->prepend($text);
    }

    public function appendOption($value, $text = FALSE) {
        return $this->append(new Option($value, $text));
    }

    public function appendOptions($array, $ignore_indexes = TRUE) {
        foreach ($array as $value => $text) {
            $option = new Option(FALSE, $text);
            
            if (!$ignore_indexes) {
                $option->value($value);
            }

            $this->append($option);
        }
        
        return $this;
    }

}
