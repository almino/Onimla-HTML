<?php

namespace Onimla\HTML\Traits;

trait Href {

    public function href($url = FALSE) {
        return $this->attr(__FUNCTION__, $url);
    }

}
