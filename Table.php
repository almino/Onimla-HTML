<?php

namespace Onimla\HTML;

#require_once 'Element.class.php';

class Table extends Element {

    public function __construct($children = FALSE, $attr = FALSE) {
        parent::__construct('table', $attr, $children);
    }

    public static function fromArray($array, $headings = FALSE) {
        $table = FALSE;

        if (count($array) > 0) {
            $table = new Table();
            $thead = new Element('thead');
            $tbody = new Element('tbody');
            $tr = new Element('tr');

            foreach (array_keys(current($array)) as $key) {
                $th = new Element('th');

                $th->text((is_array($headings) AND key_exists($key, $headings)) ? $attr[$key] : $key);

                $tr->append($th);
            }

            $thead->append($tr);
            $table->append($thead, $tbody);


            foreach ($array as $row) {
                $tr = new Element('tr');

                foreach ($row as $cel) {
                    $td = new Element('td');

                    $td->text($cel);
                    
                    $tr->append($td);
                }
                
                $tbody->append($tr);
            }
        }
        
        return $table;
    }

}
