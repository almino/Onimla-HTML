<?php

namespace Onimla\HTML;

/**
 * Attributes for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
interface HasAttribute {

    public function attr($name, $value = FALSE, $output = FALSE);

    /**
     * Store arbitrary data associated with the matched elements or return the
     * value at the named data store for the first element in the set of
     * matched elements.
     * @param string $key
     * @param string $value
     * @param string $output
     * @return Element
     */
    public function data($key, $value = FALSE, $output = 'encode');

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
