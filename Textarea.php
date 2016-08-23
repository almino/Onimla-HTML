<?php

namespace Onimla\HTML;

use Onimla\HTML\Polymorphism\UserInput;

class Textarea extends Element implements UserInput {

    use Traits\Name;

    public function __construct($name, $value = FALSE, $rows = FALSE, $attr = NULL, $children = FALSE) {
        parent::__construct('textarea', $attr, $children);
        $this->name($name);
        $this->value($value);
        $this->rows($rows);
        $this->selfClose(FALSE);
    }

    public function disabled() {
        return $this->isDisabled();
    }

    public function disable() {
        $this->setDisabled();
        return $this;
    }

    public function enable() {
        $this->unsetDisabled();
        return $this;
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
        return $this->text(...func_get_args());
    }

    public function isDisabled() {
        return (bool) $this->attr(__FUNCTION__);
    }

    public function isNotReadOnly() {
        return !$this->isReadOnly();
    }

    public function isNotRequired() {
        return !$this->isRequired();
    }

    public function isReadOnly() {
        return (bool) $this->attr('readonly');
    }

    public function isRequired() {
        return (bool) $this->attr('required');
    }

    public function isValueSet() {
        return $this->getAttribute('value')->isValueSet();
    }

    public function readOnly() {
        return $this->isReadOnly();
    }

    public function required() {
        return $this->isRequired();
    }

    public function setDisabled() {
        $this->attr('disabled', 'disabled');
    }

    public function setReadOnly() {
        $this->attr('readonly', 'readonly');
    }

    public function setRequired() {
        $this->attr('required', 'required');
    }

    public function type($value = FALSE) {
        if ($value === FALSE) {
            return 'textarea';
        }

        return $this;
    }

    public function unsetDisabled() {
        $this->removeAttr('disabled');
    }

    public function unsetReadOnly() {
        $this->removeAttr('readonly');
    }

    public function unsetRequired() {
        $this->removeAttr('required');
    }

}
