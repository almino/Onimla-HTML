<?php

namespace Onimla\HTML;

use Onimla\HTML\Attribute;
use Onimla\HTML\Polymorphism\UserInput;

class Input extends Element implements userInput {

    use Traits\Name;

    public function __construct($name = FALSE, $value = FALSE, $type = 'text', $attr = FALSE) {
        parent::__construct('input', $attr);
        $this->type($type);
        $this->name($name);
        $this->value($value);
        $this->selfClose(TRUE);
    }

    public function accept($mimeType = FALSE) {
        if ($mimeType === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $mimeType);
    }

    public function alt($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value);
    }

    public function setAutoFocus() {
        $this->attr('autofocus', 'autofocus');
    }

    public function unsetAutoFocus() {
        $this->removeAttr('autofocus');
    }

    public function isAutoFocus() {
        return $this->attr('autofocus');
    }

    public function autofocus() {
        return $this->isAutoFocus();
    }

    public function setDisabled() {
        $this->attr('disabled', 'disabled');
    }

    public function unsetDisabled() {
        $this->removeAttr('disabled');
    }

    public function isDisabled() {
        return $this->attr('autofocus');
    }

    public function disabled() {
        return $this->isDisabled();
    }

    public function disable() {
        return $this->setDisabled();
    }

    public function enable() {
        return $this->unsetDisabled();
    }

    public function maxlength($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value, 'int');
    }

    public function placeholder($value = FALSE) {
        if ($value === FALSE) {
            return $this->getAttributeValue(__FUNCTION__);
        }

        return $this->setAttributeValue(__FUNCTION__, $value, 'html');
    }

    public function readOnly() {
        return $this->setReadOnly();
    }

    public function isReadOnly() {
        return $this->setReadOnly();
    }

    public function isNotReadOnly() {
        return $this->unsetReadOnly();
    }

    public function setReadOnly() {
        return $this->attr(new Attribute('readonly'));
    }

    public function unsetReadOnly() {
        return $this->removeAttr('readonly');
    }

    public function required() {
        return $this->isRequired();
    }

    public function isRequired() {
        return $this->attr(new Attribute('required'));
    }

    public function isNotRequired() {
        return $this->removeAttr('required');
    }

    public function setRequired() {
        return $this->isRequired();
    }

    public function unsetRequired() {
        return $this->isNotRequired();
    }

    public function size($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value, 'int');
    }

    public function src($url = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value);
    }

    public function type($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value);
    }

    /**
     * Specifies the value of an input element
     * @see http://www.w3schools.com/tags/att_input_value.asp
     * @param string $value the value of the element
     * @param bool $verify if false, it will not use the http://php.net/empty
     * function to check the value
     * @return string the current value of the element
     */
    public function value($value = FALSE) {
        if ($value === FALSE) {
            return $this->getAttributeValue(__FUNCTION__);
        }

        return $this->setAttributeValue(__FUNCTION__, $value, 'html');
    }

    public function isValueSet() {
        $value = $this->getAttribute('value');

        if ($value === FALSE) {
            return FALSE;
        }
        
        return $value->isValueSet();
    }

}
