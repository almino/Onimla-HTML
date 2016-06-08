<?php

namespace Onimla\HTML\Attribute;

/*
require_once implode(DIRECTORY_SEPARATOR, array(
            substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 6),
            'HTML',
            'Attribute.class.php',
        ));
*/

/**
 * Type attribute for an HTML Input.
 *
 * @author Almino Melo at gmail dot com
 */
class Identifier extends \Onimla\HTML\Attribute {

    public function __construct($value = FALSE) {
        parent::__construct('id', $value);
    }

    public function getValue($output = TRUE) {
        if ($output === TRUE) {
            return self::safeValue($this->getValue(FALSE));
        }

        return parent::getValue($output);
    }

    public static function safeValue($value) {
        return preg_replace('/[^\w\d\-]/', '', $value);
    }
    
    public function selector() {
        return '#' . $this->getValue();
    }

}
