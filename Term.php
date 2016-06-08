<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

/**
 * The &lt;dt&gt; tag defines a term/name in a description list.
 * The &lt;dt&gt; tag is used in conjunction with &lt;dl&gt;
 * (defines a description list) and &lt;dd&gt; (describes each term/name).
 */
class Term extends Element {

    public function __construct($text, $classes = FALSE) {
        parent::__construct('dt');
        $this->text($text);
        $this->addClass($classes);
        $this->selfClose(FALSE);
    }

}
