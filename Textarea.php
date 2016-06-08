<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Textarea extends Element {

    public function __construct($name, $value = FALSE, $rows = FALSE, $attr = NULL, $children = FALSE) {
        parent::__construct('textarea', $attr, $children);
        $this->name($name);
        $this->value($value);
        $this->rows($rows);
        $this->selfClose(FALSE);
    }

    public function name($value = FALSE) {
        return $this->attr('name', $value, 'safe');
    }

    public function disabled() {
        return (bool) $this->attr(__FUNCTION__);
    }
    
    public function disable() {
        $this->attr('disabled', 'disabled');
    }

    public function enable() {
        return $this->removeAttr('disabled');
    }

    public function rows($value = FALSE) {
        return $this->attr('rows', $value, 'int');
    }

    /**
     * A text area can hold an unlimited number of characters
     * @see http://www.w3schools.com/tags/tag_textarea.asp
     * @param string $value multi-line text (calls <code>Element::text()</code>)
     * @return Textarea|string method chaining or the value
     */
    public function value($value = FALSE) {
        return $this->text($value);
    }

}
