<?php

namespace Onimla\HTML\Traits;

trait Href
{

    /**
     * @param string $url
     * @return self
     */
    public function href($url = FALSE)
    {
        return $this->attr(__FUNCTION__, $url);
    }

}
