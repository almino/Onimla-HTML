<?php

namespace Onimla\HTML;

#require_once 'Hidden.class.php';

class CrossSiteRequestForgery extends Hidden {

    public function __construct($name = FALSE, $value = FALSE, $attr = NULL) {
        if (function_exists('get_instance')
                AND property_exists(get_instance(), 'security')
                AND is_callable(array(get_instance()->security, 'get_csrf_hash'))
                AND is_callable(array(get_instance()->security, 'get_csrf_token_name'))) {
            parent::__construct(get_instance()->security->get_csrf_token_name(), get_instance()->security->get_csrf_hash(), $attr);
        } else {
            parent::__construct($name, $value, $attr);
        }
    }

}
