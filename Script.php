<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Script extends Element {

    function __construct($src, $type = 'text/javascript') {
        parent::__construct('script');
        $this->type($type);
        $this->src($src);
        $this->selfClose(FALSE);
    }

    function charset($char_encoding = FALSE) {
        return $this->attr('charset', $char_encoding);
    }

    function defer($value = FALSE) {
        return $this->attr('defer', $value);
    }

    function src($url = FALSE) {
        return $this->attr('src', $url);
    }

    public function type($MIME_type = FALSE) {
        return $this->attr('type', $MIME_type);
    }

}
