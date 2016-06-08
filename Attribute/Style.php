<?php

namespace Onimla\HTML\Attribute;

/*
require_once implode(DIRECTORY_SEPARATOR, array(
            substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 11),
            'Attribute.class.php',
        ));
 */

/**
 * CSS's style attribute for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
class Style extends \Onimla\HTML\Attribute {

    protected $value = array();

    public function __construct($class = FALSE) {
        parent::__construct('style');
        if (func_num_args() > 0) {
            call_user_func_array(array($this, 'addValue'), func_get_args());
        }
    }

    public function getValue($output = TRUE) {
        return implode('; ', $this->value);
    }

    public function setValue($value) {
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = self::value($value);
        }
    }

    public function addValue($property, $value = FALSE) {
        if (is_array($property)) {
            # Garante que o array só tem uma dimensão
            $declarations = $this->arrayFlatten(func_get_args(), 1);
        } elseif (is_string($property) AND $value === FALSE) {
            # Supondo que toda a regra venha no primero parâmetro da função
            $declarations = self::value($property);
        } else {
            # Caso venha somente uma regra, transforma em array
            $declarations = array($property => $value);
        }
        
        $this->value = array_merge($this->value, $declarations);
    }
    
    public static function value($value) {
        # Quebra a string
        $declarations = explode(';', $value);
        
        $result = array();
        
        foreach ($declarations as $declaration) {
            list($property, $value) = explode(':', $declaration);
            $result[$property] = $value;
        }
        
        return $result;
    }

}
