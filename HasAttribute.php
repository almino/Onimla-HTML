<?php

namespace Onimla\HTML;

/**
 * Attributes for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
interface HasAttribute {

    public function attr($name, $value = FALSE, $output = FALSE);

    public function removeAttr($name);

    public function matchAttr($attr, $regexOrString, $level = FALSE);

    public function matchClass($classes, $level = FALSE);

    public function &findByAttr($attr, $value);

    public function &findByName($value);

    public function &findById($value);

    public function getClassAttribute();

    public function addClass($class);

    public function removeClass($class);

    public function id($value = FALSE);
}
