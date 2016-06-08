<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

/**
 * The &lt;dd&gt; tag is used to describe a term/name in a description list.
 * The &lt;dd&gt; tag is used in conjunction with &lt;dl&gt; (defines a description list)
 * and &lt;dt&gt; (defines terms/names).
 * Inside a &lt;dd&gt; tag you can put paragraphs, line breaks, images, links,
 * lists, etc.
 */
class Description extends Element {

    public function __construct($children = FALSE, $attr = NULL) {
        parent::__construct('dd', $attr, $children);
        $this->selfClose(FALSE);
    }

}
