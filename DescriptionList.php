<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class DescriptionList extends Element {

    public function __construct($children = FALSE, $attr = NULL) {
        parent::__construct('dl', $attr, $children);
        $this->selfClose(FALSE);
    }
    
    public function appendItem($term, $description) {
        if (!$term instanceof Element) {
            $term = new Term($term);
        }
        
        if ($description instanceof Element) {
            $description = new Description($description);
        } else {
            $text = $description;
            $description = new Description();
            $description->text($text);
        }
        
        $this->append($term, $description);
    }

}
