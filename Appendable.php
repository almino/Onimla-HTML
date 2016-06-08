<?php

namespace Onimla\HTML;

/**
 * Basics for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
interface Appendable {
    public function __clone();
    public function __toString();
    public function prepend($children);
    public function prependTo($parent);
    public function append($children);
    public function appendTo($parent);
    public function removeChild($children);
    public function removeChildren();
    public function first($child = FALSE);
    public function last($child = FALSE);
    public function isChild($child);
    public function each($callableOrMethod, $params);
    public function merge($arrayOrInstance);
    
}
