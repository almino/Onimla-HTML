<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Form extends Element {

    public function __construct($method = FALSE, $action = FALSE) {
        parent::__construct('form');
        $this->action($action);
        $this->method($method);
        $this->selfClose(FALSE);
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
