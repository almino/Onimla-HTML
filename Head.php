<?php

namespace Onimla\HTML;

class Head extends Element {

    public $charset = 'utf-8';

    public function __construct($title = FALSE) {
        parent::__construct('head');

        # <meta charset="utf-8">
        $this->append(
                (new Meta())
                        ->attr(new Attribute('charset', $this->charset))
        );
        $this->append(
                (new Meta('ie=edge'))
                        ->httpEquiv('x-ua-compatible')
        );
        $this->append(
                (new Meta('width=device-width, initial-scale=1'))
                        ->attr(new Attribute('name', 'viewport'))
        );

        $this->title($title);

        $this->selfClose(FALSE);
    }

    public function title($text = FALSE) {
        if ($text === FALSE) {
            return isset($this->title) ? $this->title : FALSE;
        }

        $this->title = ($text instanceof Element) ? $text : new Title($text);

        return $this;
    }

}
