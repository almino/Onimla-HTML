<?php

namespace OOHTML\Attribute;

#require_once substr(__DIR__, 0, strpos(__DIR__, 'OOHTML')) . 'OOHTML/Attribute.class.php';

/**
 * Type attribute for an HTML Input.
 *
 * @author Almino Melo at gmail dot com
 */
class Type extends \OOHTML\Attribute {

    public function __construct($value = FALSE) {
        parent::__construct('type', $value);
    }

    public function getValue($output = TRUE) {
        if ($output === TRUE) {
            return self::safeValue($this->getValue(FALSE));
        }

        return parent::getValue($output);
    }

    public static function safeValue($value) {
        if (preg_match('/button|checkbox|color|date|datetime|datetime-local'
                        . '|email|file|hidden|image|month|number|password|radio'
                        . '|range|reset|search|submit|tel|text|time|url|week/', $value)) {
            return $value;
        }
        
        return 'text';
    }

}
