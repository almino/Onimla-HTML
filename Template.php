<?php

namespace Onimla\HTML;

class Template extends Node {

    public $doctype = '<!DOCTYPE html>';

    public function __construct($children = FALSE) {
        parent::__construct();
        
        call_user_func_array(array($this->body(), 'append'), func_get_args());
    }
    
    public function __toString() {
        return implode(PHP_EOL, array($this->doctype, $this->html()));
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

}
