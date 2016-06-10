<?php

namespace Onimla\HTML\Attribute;

/*
  require_once implode(DIRECTORY_SEPARATOR, array(
  substr(__DIR__, 0, strpos(__DIR__, 'Onimla') + 11),
  'Attribute.class.php',
  ));
 */

/**
 * Class attribute for an HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
class Klass extends \Onimla\HTML\Attribute {

    protected $value = array();

    public function __construct($class = FALSE) {
        parent::__construct('class');
        $this->setOutput('safe');
        if (func_num_args() > 0) {
            call_user_func_array(array($this, 'addValue'), func_get_args());
        }
    }

    public function __toString() {
        if (strlen($this->getValue(TRUE)) < 1) {
            return '';
        }

        return parent::__toString();
    }

    public function selector() {
        $value = $this->getValue();
        if (strlen($value) < 1) {
            return '';
        }

        #return '.' . implode('.', preg_split('/\s/', $value, -1, PREG_SPLIT_NO_EMPTY));
        return '.' . implode('.', explode(' ', $value));
    }

    public function prepValue($value) {
        $values = \Onimla\HTML\Node::arrayFlatten(func_get_args());

        array_walk($values, function(&$val) {
            $val = explode(' ', $val);
            return $val;
        });

        return \Onimla\HTML\Node::arrayFlatten($values);
    }

    public static function outputValue($value) {
        $values = array_filter(\Onimla\HTML\Node::arrayFlatten(func_get_args()), 'strlen');
        return implode(' ', array_map(array(__CLASS__, 'safeValue'), $values));
    }

    public function getValue($output = TRUE) {
        $values = array_filter($this->value, 'strlen');

        if ($output === TRUE) {
            return implode(' ', array_map(array(__CLASS__, 'safeValue'), $values));
        }

        return parent::getValue($output);
    }

    public function setValue($value) {
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = array($value);
        }
    }

    public function addValue($value) {
        $this->value = array_merge($this->value, call_user_func_array(array($this, 'prepValue'), func_get_args()));
        return $this;
    }

    public function addClass($value) {
        return call_user_func_array(array($this, 'addValue'), func_get_args());
    }

    public function prepend($classes) {
        foreach (array_reverse(call_user_func_array(array($this, 'prepValue'), func_get_args())) as $class) {
            if (count($this->value) < 1) {
                $this->append($class);
            } else {
                self::log("Adding `{$class}` to the begining of the array", TRUE);
                # Coloca o parâmetro no início do array
                array_unshift($this->value, $class);
                # !!! Não precisa reatribuir $this->value
            }
        }

        return $this;
    }

    public function append($class) {
        self::log("Adding `{$class}` to the eng of the array", TRUE);
        return call_user_func_array(array($this, 'addValue'), func_get_args());
    }

    public function before($class, $classes) {
        self::log("Adding classes before `{$class}`", TRUE);

        $newClasses = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($newClasses);
        # Garante que não há várias dimensões no array
        $newClasses = call_user_func_array(array($this, 'prepValue'), $newClasses);
        self::log('Classes before to be added: .' . implode('.', $newClasses));

        $tmp = array_values($this->value);

        $pos = array_search($class, $tmp);

        if ($pos === FALSE) {
            throw new \Exception("Can\'t find \$class ({$class}).");
        } elseif ($pos === 0) {
            $this->setValue(array_merge($newClasses, $this->value));
        } else {
            $before = array_slice($this->value, 0, $pos);
            $after = array_slice($this->value, $pos);

            $this->setValue(array_merge($before, $newClasses, $after));
        }

        return $this;
    }

    public function after($class, $classes) {
        $newClasses = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($newClasses);
        # Garante que não há várias dimensões no array
        $newClasses = call_user_func_array(array($this, 'prepValue'), $newClasses);

        $tmp = array_values($this->value);

        $pos = array_search($class, $tmp);

        if ($pos === FALSE) {
            throw new \Exception("Can\'t find \$class ({$class}).");
            /*
              } elseif ($pos === 0) {
              call_user_func_array(array($this, 'addValue'), func_get_args());
             */
        } else {
            $before = array_slice($this->value, 0, $pos + 1);
            $after = array_slice($this->value, $pos + 1);

            $this->setValue(array_merge($before, $newClasses, $after));
        }

        return $this;
    }

    public function removeClass($class) {
        foreach (\Onimla\HTML\Node::arrayFlatten(func_get_args()) as $class) {
            $key = array_search($class, $this->value);
            if ($key !== FALSE) {
                unset($this->value[$key]);
            }
        }

        return $this;
    }

    /**
     * WARNING! This will reset / rewrite all of your classes.
     * Remove $class if they appear in the same exact order.
     * @param string $class as many as you want
     * @return \Onimla\HTML\Attribute\Klass
     */
    public function strictRemoveClass($class) {
        $remove = call_user_func_array(array(__CLASS__, 'outputValue'), func_get_args());
        $current = $this->getValue();
        var_dump($current);
        
        $this->setValue(explode(' ', preg_replace("/{$remove}/", '', $current)));
        
        return $this;
    }

    public static function safeValue($value) {
        return preg_replace('/[^_a-zA-Z0-9\-]/', '', $value);
    }

}
