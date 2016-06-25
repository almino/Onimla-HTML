<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Meta extends Element {

    function __construct($content = FALSE) {
        parent::__construct('meta');
        $this->content($content);
    }

    function httpEquiv($value = FALSE) {
        $attrName = 'http-equiv';

        if ($value === FALSE) {
            return $this->attr($attrName);
        }

        $this->attr(new Attribute($attrName, $value));

        return $this;
    }

    function content($value = FALSE) {
        $attrName = 'content';

        if ($value === FALSE) {
            return $this->attr($attrName);
        }

        $this->attr(new Attribute($attrName, $value));

        return $this;
    }

}