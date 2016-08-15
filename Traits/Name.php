<?php

namespace Onimla\HTML\Traits;

trait Name {

    /**
     * Get / set value for <code>name</code> attribute
     * @param string $value
     * @return string|Element
     */
    public function name($value = FALSE) {
        if ($value === FALSE) {
            return $this->getAttributeValue(__FUNCTION__);
        }

        $attr = new Attribute(__FUNCTION__, $value);
        $attr->setOutput('safe');

        $this->attr($attr);

        return $this;
    }

}
