<?php

namespace Onimla\HTML\Attribute;

/*
require_once implode(DIRECTORY_SEPARATOR, array(
            substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 11),
            'Attribute.class.php',
        ));
*/

/**
 * <code>data-</code> attribute for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
class Data extends \Onimla\HTML\Attribute {

    public function __construct($key = FALSE, $value = FALSE) {
        parent::__construct($key, $value);
    }

    public function getName() {
        return 'data-' . parent::getName();
    }

}
