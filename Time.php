<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Time extends Element {

    public function __construct($text = FALSE, $datetime = FALSE) {
        self::log('Construct of ' . get_class($this) . ' called.', TRUE);

        parent::__construct('time');
        self::log('Setting datetime attribute.');
        $this->dateTime($datetime);
        self::log('Setting text.');
        $this->text($text);
        self::log('Setting closing tag.');
        $this->selfClose(FALSE);

        self::log('End of ' . __METHOD__ . ' reached.');
    }

    /**
     * Get or set <code>datetime</code> attribute
     * @param string $value optional
     * @return \Onimla\HTML\Time|Attribute
     */
    public function dateTime($value = FALSE) {
        $attrName = 'datetime';
        self::log('Getting or setting datetime attribute for ' . get_class($this), TRUE);

        if (strlen($value) < 5) {
            self::log('Value passed is invalid, returning old value.');
            self::log('$value = ' . var_export($value, TRUE));
            return $this->attr($attrName);
        }

        $attr = new Attribute($attrName, $value);
        $attr->setOutput('encode');

        $this->attr($attr);

        return $this;
    }

}
