<?php

namespace Onimla\HTML;

class Button extends Element {
    
    use Traits\Name;

    function __construct($text = FALSE, $type = 'button') {
        parent::__construct('button');
        $this->type($type);
        $this->text($text);
        $this->selfClose(FALSE);
    }

    /**
     * Specifies the value of an input element
     * @see http://www.w3schools.com/tags/att_input_value.asp
     * @param string $value the value of the element
     * @return string|Button the current value of the element OR method chaining
     */
    function value($value = FALSE) {
        return $this->attr('value', $value);
    }

    /**
     * @param string $value
     * @return string|Button
     */
    function type($value = NULL) {
        return $this->attr('type', $value, 'safe');
    }

    /**
     * @param string $value
     * @return string|Button
     */
    public function form($value = FALSE) {
        if (is_object($value) AND is_callable(array($value, 'id'))) {
            $value = ($value->id() === FALSE AND is_callable(array($value, 'uniqid'))) ? $value->uniqid()->id() : $value->id();
        }
        
        return $this->attr('form', $value, 'safe');
    }

    /**
     * @return bool
     */
    public function enabled() {
        return !$this->attr('disabled');
    }

    /**
     * @return bool
     */
    public function disabled() {
        return (bool) $this->attr('disabled');
    }

    /**
     * @return Button
     */
    public function enable() {
        return $this->removeAttr('disabled');
    }

    /**
     * 
     * @return Button
     */
    public function disable() {
        return $this->attr('disabled', 'disabled');
    }

}
