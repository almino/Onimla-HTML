<?php

namespace Onimla\HTML;

/**
 * Attributes for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
interface HasAttribute {

    public function attr($name, $value = FALSE, $output = FALSE);

    public function matchAttr($name, $regex, $level = FALSE);

    public function &findByAttr($attr, $value);

    public function &findByName($value);

    public function &findById($value);
    
    public function matchClass($classes, $level = FALSE);
}
