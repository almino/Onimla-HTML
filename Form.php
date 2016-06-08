<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Form extends Element {

    public function __construct($method = FALSE, $action = FALSE) {
        parent::__construct('form');
        $this->action($action);
        $this->method($method);
        $this->selfClose(FALSE);

        if (function_exists('get_instance')
                AND property_exists(get_instance(), 'security')
                AND method_exists(get_instance()->security, 'get_csrf_hash')
                AND get_instance()->security->get_csrf_hash()) {
            #require_once 'CrossSiteRequestForgery.class.php';
            $this->append(new CrossSiteRequestForgery());
        }
    }

    public function action($url = FALSE) {
        if ($url === FALSE) {
            return $this->attr(__FUNCTION__);
        }
        
        return $this->attr(__FUNCTION__, $url);
    }

    public function method($value = FALSE) {
        if ($value === FALSE) {
            return $this->attr(__FUNCTION__);
        }
        
        return $this->attr(__FUNCTION__, $value);
    }

}
