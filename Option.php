<?php

namespace Onimla\HTML;

/*
  require_once 'Element.class.php';
  require_once 'Select.class.php';
 */

class Option extends Element {

    public function __construct($value = FALSE, $text = FALSE) {
        parent::__construct('option');
        $this->value($value);
        $this->text($text);
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

    public function label($text = FALSE) {
        return $this->attr('label', $text, 'encode');
    }

    /**
     * Set the attribute <code>selected</code> to <code>true</code>
     * @return Option
     */
    public function select() {
        return $this->attr('selected', 'selected');
    }

    /**
     * The value for the attribute <code>selected</code>
     * @return bool
     */
    public function selected() {
        return $this->attr('selected');
    }

    /**
     * Unset the attribute <code>selected</code> (set to <code>fale</code>)
     * @return Option
     */
    public function deselect() {
        return $this->removeAttr('selected');
    }

    public function deselected() {
        return (bool) !$this->attr('selected');
    }

    public function value($value = FALSE) {
        if (strlen($value) < 1) {
            return $this->attr(__FUNCTION__);
        }

        return $this->attr(__FUNCTION__, $value);
    }

    public function create($array, $ignoreIndexes = TRUE) {
        $result = new Node();

        foreach ($array as $value => $text) {
            $option = $text instanceof Element ? $text : new self(FALSE, $text);

            if (!$ignoreIndexes) {
                $option->attr('value', $value);
            }

            $result->$value = $text;
        }

        return $result;
    }

}
