<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Input extends Element {

    public function __construct($name, $value = FALSE, $type = 'text', $attr = FALSE) {
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

    public function setAutoFocus($set = TRUE) {
        return $this->attr('autofocus', 'autofocus');
    }

    public function unsetAutoFocus($set = TRUE) {
        return $this->removeAttr('autofocus');
    }

    public function isAutoFocus($set = TRUE) {
        return $this->attr('autofocus');
    }

    public function disabled($set = TRUE) {
        return $set ? $this->attr('disabled', 'disabled') : $this->removeAttr('checked');
    }

    public function disable() {
        return $this->attr('disabled', 'disabled');
    }

    public function enable() {
        return $this->removeAttr('disabled');
    }

    public function maxlength($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value, 'int');
    }

    public function name($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        /*
          require_once implode(DIRECTORY_SEPARATOR, array(
          substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 6),
          'HTML',
          'Attribute.class.php',
          ));
         */

        $attr = new \Onimla\HTML\Attribute(__FUNCTION__, $value);
        $attr->setOutput('safe');

        $this->attr($attr);

        return $this;
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
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value);
    }

}
