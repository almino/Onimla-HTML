<?php

namespace Onimla\HTML\Attribute;

class AlternateText extends \Onimla\HTML\Attribute {

    protected $output = 'htmlentities';

    public function __construct($value = FALSE) {
        parent::__construct('alt', $value);
    }

}
