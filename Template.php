<?php

namespace Onimla\HTML;

/**
 * @property Element $html root element
 * @property Head $head
 * @property Element $body
 */
class Template extends Node {

    /**
     * Appears before root element
     * @var string to be echoed
     */
    public $doctype = '<!DOCTYPE html>';

    public function __construct($children = FALSE) {
        parent::__construct();

        if (func_num_args() > 0) {
            call_user_func_array(array($this->body(), 'append'), func_get_args());
        }
    }

    public function __toString() {
        return implode(PHP_EOL, array($this->doctype, $this->html()));
    }

    public function __get($name) {
        if (!isset($this->$name) AND method_exists($this, $name)) {
            return call_user_func(array($this, $name));
        }

        return parent::__get($name);
    }

    public function html($instance = FALSE) {
        if (!isset($this->html)) {
            $this->html = new Element('html');
            $this->html->append($this->head(), $this->body());
        }

        if ($instance === FALSE) {
            return $this->html;
        }

        $this->html = $instance;

        return $this;
    }

    /**
     * @param Element $instance
     * @return \Onimla\HTML\Template|\Onimla\HTML\Head
     */
    public function head($instance = FALSE) {
        if (!isset($this->head)) {
            $this->head = new Head();
        }

        if ($instance === FALSE) {
            return $this->head;
        }

        $this->head = $instance;

        return $this;
    }

    public function title($text = FALSE) {
        $this->head->title($text);

        return ($text === FALSE) ? $this->head->title() : $this;
    }

    public function body($instance = FALSE) {
        if (!isset($this->body)) {
            $this->body = new Element('body');
        }

        if ($instance === FALSE) {
            return $this->body;
        }

        $this->body = $instance;

        return $this;
    }

    public function append($children) {
        call_user_func_array(array($this->body, 'append'), func_get_args());
        return $this;
    }

}
