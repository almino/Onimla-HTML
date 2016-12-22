<?php

namespace Onimla\HTML\Traits;

trait Target
{

    /**
     * @param string $frameName
     * @return self
     */
    public function target($frameName = FALSE)
    {
        return $this->attr(__FUNCTION__, $frameName);
    }

}
