<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Meta extends Element {

    function __construct($http_equiv = 'Content-Type', $content = 'text/html;charset=UTF-8') {
        parent::__construct('meta');
        $this->http_equiv($http_equiv);
        $this->content($content);
    }

    function http_equiv($http_equiv = NULL) {
        if (!empty($http_equiv)) {
            $this->attr('http-equiv', $http_equiv);
        }

        return $this->attr('http-equiv');
    }

    function content($content = NULL) {
        if (!empty($content)) {
            $this->attr('content', $content);
        }

        return $this->attr('content');
    }

}

?>