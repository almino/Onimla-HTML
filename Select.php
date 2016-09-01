<?php

namespace Onimla\HTML;

use Onimla\HTML\Polymorphism\UserInput;

class Select extends Element implements UserInput {

    use Traits\Name;

    /**
     * @param string $name
     * @param array|Element $options
     */
    public function __construct($name, $options = FALSE) {
        parent::__construct('select');
        $this->name($name);
        $this->selfClose(FALSE);
        if ($options !== FALSE) {
            $this->appendOptions($options);
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

    public function isDisabled() {
        return $this->hasAttribute(Constant::DISABLED);
    }

    public function isNotReadOnly() {
        return !$this->hasAttribute(Constant::READONLY);
    }

    public function isNotRequired() {
        return !$this->hasAttribute(Constant::REQUIRED);
    }

    public function isReadOnly() {
        return $this->hasAttribute(Constant::READONLY);
    }

    public function isRequired() {
        return $this->hasAttribute(Constant::REQUIRED);
    }

    public function isValueSet() {
        return count($this->findByAttr(Constant::SELECTED, Constant::SELECTED)) > 0;
    }

    public function readOnly() {
        return $this->readOnly();
    }

    public function setDisabled() {
        $this->setAttributeValue(Constant::DISABLED, Constant::DISABLED, 'none');
    }

    public function setReadOnly() {
        $this->setAttributeValue(Constant::READONLY, Constant::READONLY, 'none');
    }

    public function setRequired() {
        $this->setAttributeValue(Constant::REQUIRED, Constant::REQUIRED, 'none');
    }

    public function type($value = FALSE) {
        if ($value === FALSE) {
            return $this->name;
        }
        
        return $this;
    }

    public function unsetDisabled() {
        $this->removeAttr(Constant::DISABLED);
    }

    public function unsetReadOnly() {
        $this->removeAttr(Constant::READONLY);
    }

    public function unsetRequired() {
        $this->removeAttr(Constant::REQUIRED);
    }

    /**
     * Get selected <code>&lt;option&gt;</code> or set an
     * <code>&lt;option&gt;</code> as selected.
     * @param string $value optional
     * @return \Onimla\HTML\Select|string
     */
    public function value($value = FALSE) {
        if ($value === FALSE) {
            $found = $this->findByAttr('selected', 'selected');

            if (is_object($found) AND method_exists($found, 'attr')) {
                return $found->attr('value') === FALSE ? $found->text() : $found->attr('value');
            }

            return FALSE;
        }

        foreach ($this->getChildren() as $option) {
            /* @var $option Element */
            # Case the `value` attribute is equal to $value
            if (
                    is_object($option)
                    AND method_exists($option, 'attr')
                    AND $option->attr('value') !== FALSE
                    AND html_entity_decode($option->attr('value')) == $value
            ) {
                $this->deselectAll();
                $option->setAttributeVal('selected', 'selected');
                return $this;
            }

            # Case has no `value` attribute set
            if (
                    is_object($option)
                    AND method_exists($option, 'attr')
                    AND $option->attr('value') === FALSE
                    AND method_exists($option, 'text')
                    AND html_entity_decode($option->text()) == $value
            ) {
                $this->deselectAll();
                $option->setAttributeVal('selected', 'selected');
                return $this;
            }
        }

        return $this;
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

    public function appendOptions($array, $ignoreIndexes = TRUE) {
        foreach ($array as $value => $text) {
            $option = $text instanceof Element ? $text : new Option(FALSE, $text);

            if (!$ignoreIndexes) {
                $option->attr('value', $value);
            }

            $this->append($option);
        }

        return $this;
    }

}
