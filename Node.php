<?php

namespace Onimla\HTML;

use Countable;
use Serializable;

/**
 * An HTML node.
 *
 * @author AlminoMelo at gmail.com
 */
class Node implements Countable, Serializable {

    /**
     * String to put before the instance string
     * @var string
     */
    public $before;

    /**
     * String to put after the instance string
     * @var string
     */
    public $after;

    /**
     * @var array
     */
    protected $children = array();

    /**
     * Whether or not to store actions in a log
     * @var boolean
     */
    public static $log = FALSE;

    /**
     * Prints pretty source-code
     * @var boolean
     */
    public $indentSource = FALSE;

    const TAB = "  ";

    public function __construct($children = FALSE) {
        self::log('Created new instance of `' . get_class($this) . '`.', TRUE);

        $children = self::filterChildren(func_get_args());

        if (count($children)) {
            call_user_func_array(array($this, 'append'), $children);
        }
    }

    public function __destruct() {
        self::log('Destroyed instance of `' . get_class($this) . '`.', TRUE);
    }

    public function __get($name) {
        self::log("Using magical method to GET a child named `{$name}`.", TRUE);

        if (array_key_exists($name, $this->children)) {
            return $this->children[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'], E_USER_NOTICE);

        return FALSE;
    }

    public function __set($name, $value) {
        self::log("Using magical method to SET a child named `{$name}`.", TRUE);
        $this->children[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->children[$name]);
    }

    public function __clone() {
        foreach ($this->children as $key => $child) {
            $this->children[$key] = is_object($child) ? clone $child : $child;
        }
    }

    public function __toString() {
        self::log('Casting instance of ' . get_class($this) . ' to `string`.', TRUE);

        $glue = $this->indentSource ? $this->after . PHP_EOL . $this->before : "{$this->after}{$this->before}";

        return $this->before . implode($glue, $this->children) . $this->after;
    }

    public function serialize() {
        return serialize(array(
            $this->before,
            $this->after,
            $this->children,
        ));
    }

    public function unserialize($serialized) {
        list(
                $this->before,
                $this->after,
                $this->children,
                ) = unserialize($serialized);
    }

    /**
     * Same as PHP's <code>array_merge</code>
     * @param self|array $arrayOrInstance As many as you want
     */
    public function merge($arrayOrInstance) {
        foreach (self::arrayFlatten(func_get_args()) as $arrayOrInstance) {
            if ($arrayOrInstance instanceof self) {
                $arrayOrInstance = $arrayOrInstance->getChildren();
            }

            call_user_func_array(array($this, 'append'), $arrayOrInstance);
        }
    }

    public function length() {
        return count($this->children);
    }

    public function countChildren() {
        return call_user_func_array(array($this, 'length'), func_get_args());
    }

    public function count() {
        return $this->length();
    }

    public function getChildren() {
        return $this->children;
    }

    public function eq($index) {
        # Reseta as chaves/índices dos filhos
        $temp = array_values($this->children);

        if (key_exists($index, $temp)) {
            return $temp[$index];
        }

        return FALSE;
    }

    public function index($index) {
        if (key_exists($index, $this->children)) {
            return $this->children[$index];
        }

        return FALSE;
    }

    protected final function addChildren($children) {
        # Reduz todos os elementos passados para a função a um array de uma dimensão
        $children = self::filterChildren(func_get_args());

        foreach ($children as $child) {
            $this->children[] = ($child instanceof self) ? $child : (string) $child;
        }

        return $this;
    }

    public function prepend($children) {
        foreach (array_reverse(self::arrayFlatten(func_get_args())) as $child) {
            if ($this->length() < 1) {
                $this->append($child);
            } else {
                # Coloca o parâmetro no início do array
                array_unshift($this->children, $child);
                # !!! Não precisa reatribuir $this->children
            }

            /*
              if (method_exists($child, 'setParent')) {
              $child->setParent($this);
              }
             */
        }

        return $this;
    }

    public function prependTo($parent) {
        foreach (self::arrayFlatten(func_get_args()) as $parent) {
            if (method_exists($parent, 'prepend')) {
                $parent->prepend($this);
            }
        }

        return $this;
    }

    public function append($children) {
        return call_user_func_array(array($this, 'addChildren'), func_get_args());
    }

    public function appendTo($parent) {
        foreach (self::arrayFlatten(func_get_args()) as $parent) {
            if (method_exists($parent, 'append')) {
                $parent->append($this);
            }
        }

        return $this;
    }

    public function removeChild($grandchildren) {
        # Se não há filhos, nada a fazer
        if ($this->length() < 1) {
            self::log("No child at \"{$this->path()}\"", TRUE);
            return FALSE;
        }

        # Retorno padrão
        $removed = new Node();

        # Percorre os parâmetros recebidos
        foreach (self::arrayFlatten(func_get_args()) as $c) {
            if (is_bool($c) AND function_exists('log_hr') AND function_exists('log_backtrace')) {
                self::log('No booleans accepted here!', TRUE, 'error');
                continue;
            }

            # Procura nos filhos
            $key = array_search($c, $this->children);
            # Se encontrar
            if ($key !== FALSE) {
                # Coloca o filho no array
                $removed->append($this->children[$key]);

                if (method_exists($this->children[$key], 'unsetParent')) {
                    # Remove o elemento pai
                    $this->children[$key]->unsetParent();
                }

                # Remove o filho do array
                unset($this->children[$key]);

                self::log((is_object($c) AND method_exists($c, 'path') ? "\"{$c->path()}\"" : "`{$c}`") . ' REMOVED from `' . get_class($this) . '`');
            } else {
                self::log((is_object($c) AND method_exists($c, 'path') ? "\"{$c->path()}\"" : "`{$c}`") . ' NOT FOUND in `' . get_class($this) . '`');
            }

            # Procura nos netos
            foreach ($this->children as $child) {
                # Só pergunta aos netos que tem o método removeChild();
                if (is_object($child) AND method_exists($child, __FUNCTION__)) {
                    # Faz o que foi feito acima
                    $grandchildren = $child->removeChild($c);
                    # Junta os dois arrays, caso encontre algum neto para remover
                    if ($grandchildren !== FALSE AND $grandchildren->length() > 0) {
                        $removed->merge($grandchildren);
                    }
                }
            }
        }

        return $removed;
    }

    /**
     * Remove children and return it
     * @return array
     */
    public function removeChildren() {
        $children = $this->children;
        $this->children = array();
        return $children;
    }

    /**
     * Get or replace first child
     * @param string|self $child As many as you want
     * @return string|self The first child before replacement
     */
    public function first($child = FALSE) {
        /*
          # Garante que estamos trabalhando com o primeiro filho
          reset($this->children);
         */

        # Caso redefinir o primeiro filho
        if (func_num_args() > 0) {
            /*
              # Pega a chave do priemiro filho
              $key = key($this->children);
              # Coloca o filho na primeira posição do array
              $this->children[$key] = $child;

              # Method chaining
              return $this;
             */

            # Armazena temporariamente e remove o primeiro filho
            $temp = array_shift($this->children);
            # Coloca os filhos no início do array
            call_user_func_array(array($this, 'prepend'), func_get_args());

            return $temp;
        }

        /*
          # Verifica se há algum filho
          if (!empty($this->children)) {
          switch ($output) {
          case 'html':
          # Pode ser usado para debug da instância, por exemplo
          return htmlentities(current($this->children));
          case 'string':
          case 'str':
          # Por algum motivo, você quer chamar o método __toString da instância
          return (string) current($this->children);
          default:
          # Retorna o primeiro filho do jeito que ele é
          return current($this->children);
          }
          }
         */

        # Garante que estamos trabalhando com o primeiro filho
        reset($this->children);

        # Retorna o primeiro filho do jeito que ele é
        return current($this->children);

        /*
          # Caso tudo dê errado
          return FALSE;
         */
    }

    /**
     * Get or replace last child
     * @param string|self $child As many as you want
     * @return string|self The last child before replacement
     */
    public function last($child = FALSE) {
        /*
          if (!empty($this->children)) {
          # Garante que estamos trabalhando com o último filho
          end($this->children);
          }
         */

        # Caso redefinir o último filho
        if (func_num_args() > 0) {
            /*
              # Caso não haja filhos, o primeiro também é o último
              $key = empty($this->children) ? 0 : key($this->children);
              # Redefine o último filho
              $this->children[$key] = $child;
             */

            # Armazena temporariamente e remove o último filho
            $temp = array_pop($this->children);
            # Coloca os filhos no final do array
            call_user_func_array(array($this, 'append'), func_get_args());

            return $temp;
        }

        /*

          # Verifica se há algum filho
          if (!empty($this->children)) {
          switch ($output) {
          case 'encode':
          case 'html':
          # Pode ser usado para debug da instância, por exemplo
          return htmlentities(end($this->children));
          case 'string':
          case 'str':
          # Por algum motivo, você quer chamar o método __toString da instância
          return (string) end($this->children);
          default:
          # Retorna o último filho do jeito que ele é
          return end($this->children);
          }
          }
         */

        # Garante que estamos trabalhando com o último filho
        end($this->children);

        # Retorna o último filho do jeito que ele é
        return end($this->children);
    }

    public function isChild($child) {
        /*
          if (!count($this->children)) {
          return FALSE;
          }
         */

        $found = in_array($child, $this->children);

        # Caso não seja filho desta instância
        if (!$found) {
            # Pergunta aos filhos desta instância
            foreach ($this->children as $c) {
                # Verifica se o método existe na instância
                if (method_exists($c, 'isChild')) {
                    $found = $c->isChild($child);

                    # Se encontrar
                    if ($found) {
                        return TRUE;
                    }
                }
            }
        }

        # Caso tenha encontrado
        return $found;
    }

    /**
     * 
     * @param callable|string $callable e.g. function ($instance) { $instance->trim(); }
     * @param array $params optional
     * @return self
     */
    public function each($callableOrMethod, $params = FALSE) {

        $parameters = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($parameters);

        foreach ($this->children as &$child) {
            if (get_class($child) == self::class) {
                call_user_func_array(array($child, __FUNCTION__), func_get_args());
            } elseif (is_string($callableOrMethod) AND method_exists($child, $callableOrMethod)) {
                call_user_func_array(array($child, $callableOrMethod), $parameters);
            } elseif (is_callable($callableOrMethod)) {
                $callableOrMethod($child);
            }
        }

        return $this;
    }

    /**
     * Recursively reduces deep arrays to single-dimensional arrays
     * @see http://php.net/manual/pt_BR/function.array-values.php#77671
     * @param array $array
     * @param int $preserve_keys (0=>never, 1=>strings, 2=>always)
     * @param array $newArray
     * @return array
     */
    public static function arrayFlatten($array, $preserve_keys = 0, &$newArray = Array()) {
        foreach (new \ArrayIterator($array) as $key => $child) {
            if ($child instanceof \stdClass) {
                $child = (array) $child;
            }

            if (is_array($child)) {
                $newArray = self::arrayFlatten($child, $preserve_keys, $newArray);
                $newArray = & $newArray;
            } elseif ($preserve_keys + is_string($key) > 1) {
                $newArray[$key] = $child;
            } else {
                $newArray[] = $child;
            }
        }
        return $newArray;
    }

    public static function log($message, $showCaller = FALSE, $debugBacktrace = FALSE) {
        if (self::$log) {
            $dir = __DIR__ . DIRECTORY_SEPARATOR . 'logs';
            $class = str_replace('\\', '-', __CLASS__);
            $date = date('Y-m-d');
            $timestamp = $date . date(' H:i:s -- ');
            $caller = NULL;

            $destination = $dir . DIRECTORY_SEPARATOR . "{$date}_{$class}.log";
            $message = implode(PHP_EOL . str_repeat(' ', strlen($timestamp)), explode(PHP_EOL, $message));


            if ($showCaller) {
                $trace = ($debugBacktrace === FALSE) ? debug_backtrace() : $debugBacktrace;
                $trace = (object) $trace[1];
                $caller = (property_exists($trace, 'class') ? $trace->class . $trace->type : NULL)
                        . $trace->function . ' »»» ';

                #var_dump($trace->file);
                #var_dump(str_replace('\\', '/', $trace->class));
                #var_dump(strstr($trace->file, str_replace('\\', '/', $trace->class)));
                # Log file name if it differs from class name
                if (property_exists($trace, 'file') AND strstr($trace->file, str_replace('\\', DIRECTORY_SEPARATOR, $trace->class)) === FALSE) {
                    # Try to remove unecessary stuff from path string
                    $filename = trim(substr($trace->file, strlen(__DIR__)), DIRECTORY_SEPARATOR) . ':' . $trace->line . PHP_EOL . str_repeat(' ', strlen($timestamp));
                    #var_dump($filename);
                    #var_dump($caller);
                    #var_dump($message);
                    $message = implode(PHP_EOL . str_repeat(' ', strlen($caller) - 3), explode(PHP_EOL, $message));
                    $caller = $filename . $caller;
                }
                #var_dump($caller . $message);
            }

            /*
              echo '<pre>';
              echo $timestamp;
              echo $caller;
              echo $message;
              echo '</pre>';
              die();
             */

            # Creates a file, if it does not exists
            if (!file_exists($destination)) {
                mkdir($dir, 0755, TRUE);
            }

            return error_log($timestamp . $caller . trim($message) . PHP_EOL, 3, $destination);
            #return file_put_contents($filename, $message, FILE_APPEND);
        } else {
            return TRUE;
        }
    }

    public static function filterChildren($children) {
        return array_filter(self::arrayFlatten(func_get_args()), function($val) {
            return ($val !== NULL AND $val !== FALSE AND $val !== '');
        });
    }

    public static function debug(self $instance) {
        foreach (func_get_args() as $instance) {
            foreach (new \ArrayIterator($instance->getChildren()) as $key => $child) {
                if (is_object($child) AND method_exists($child, 'data')) {
                    $child->data('node:key', $key);
                }

                #$child->after .= '<!-- ' . __CLASS__ . "::\${$key} -->";
            }
        }
    }

}
