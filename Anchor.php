<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Anchor extends Element {

    public function __construct($href, $title = FALSE) {
        parent::__construct('a');
        $this->href($href);
        $this->title($title);
        $this->selfClose(FALSE);
    }

    public function href($url = FALSE) {
        if ($url !== FALSE) {
            if (function_exists('get_instance')) {
                $CI = &get_instance();
                $CI->load->helper('url');
                if (is_array($url) OR ! (strstr($url, 'http') OR ! strstr($url, 'ftp') OR $url{0} != '#' OR ! strstr($url, 'javascript:')) AND function_exists('site_url')) {
                    $url = site_url($url);
                }
            }

            return $this->attr('href', $url);
        }

        return $this->attr('href');
    }

}
