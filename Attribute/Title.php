<?php

namespace Onimla\HTML\Attribute;

/*
  require_once implode(DIRECTORY_SEPARATOR, array(
  substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 11),
  'Attribute.class.php',
  ));
 */

/**
 * 
 *
 * @author AlminoMelo at gmail.com
 */
class Title extends \Onimla\HTML\Attribute {

    protected $output = 'htmlentities';

    public function __construct($value = FALSE) {
        parent::__construct('title', $value);
    }

}
