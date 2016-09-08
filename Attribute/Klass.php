<?php

namespace Onimla\HTML\Attribute;

use Onimla\HTML\Node;

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
        $values = Node::arrayFlatten(func_get_args());

        array_walk($values, function(&$val) {
            $val = explode(' ', $val);
            return $val;
        });

        return array_filter(Node::arrayFlatten($values), 'strlen');
    }

    public static function outputValue($value) {
        # Pega todos os parâmetros
        $values = Node::arrayFlatten(func_get_args());
        # Tranforma strings com espaços em vetores
        array_walk($values, function(&$param) {
            $param = explode(' ', $param);
        });
        # Remove o que não nos intressa (vazio)
        $values = array_filter(Node::arrayFlatten($values), 'strlen');
        # Aplica a função que torna o valor apto para cada um dos parâmetros
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
        $this->value = Node::arrayFlatten(func_get_args());
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

    /**
     * 
     * @param string $classes to search to
     * @param string $newClasses as many classes to add as you want
     * @return \Onimla\HTML\Attribute\Klass
     * @throws \Exception
     */
    public function before($classes, $newClasses) {
        # Garante que as classes estão corretas
        $classes = self::outputValue(preg_split('/\s+/', $classes));
        self::log("Adding classes before `{$classes}`", TRUE);

        # Pega todos os parâmetros passados
        $newClasses = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($newClasses);
        # Garante que não há várias dimensões no array
        $newClasses = call_user_func_array(array($this, 'prepValue'), $newClasses);
        self::log('Classes before to be added: .' . implode('.', $newClasses));

        # Se algum parâmentro não foi passado, encerra por aqui
        if (count($newClasses) < 1) {
            return $this;
        }

        # Garante que o array tem índices numéricos
        #$tmp = array_values($this->value);
        # Pesquisa a classe na string contendo todas as classes
        $pos = strpos($this->getValue(TRUE), $classes);

        # Se não encontrar, ERRO!
        if ($pos === FALSE) {
            throw new \Exception("Can\'t find \$class ({$classes}).");
        } elseif ($pos === 0) {
            # Se estiver no início, mescla os vetores
            $this->setValue(array_merge($newClasses, $this->value));
        } else {
            $before = trim(substr($this->getValue(TRUE), 0, $pos));
            $pos = count(preg_split('/\s+/', $before));

            # Pega a primeira parte do vetor
            $before = array_slice($this->value, 0, $pos);
            # Pega a última parte do vetor
            $after = array_slice($this->value, $pos);

            # Junta tudo com as novas classes
            $this->setValue(array_merge($before, $newClasses, $after));
        }

        return $this;
    }

    public function after($classes, $newClasses) {
        # Garante que as classes estão corretas
        $classes = self::outputValue(preg_split('/\s+/', $classes));
        self::log("Adding classes before `{$classes}`", TRUE);

        # Pega todos os parâmetros passados
        $newClasses = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($newClasses);
        # Garante que não há várias dimensões no array
        $newClasses = call_user_func_array(array($this, 'prepValue'), $newClasses);
        self::log('Classes before to be added: .' . implode('.', $newClasses));

        # Se algum parâmentro não foi passado, encerra por aqui
        if (count($newClasses) < 1) {
            return $this;
        }

        # Garante que o array tem índices numéricos
        #$tmp = array_values($this->value);
        # Pesquisa a classe na string contendo todas as classes
        $pos = strpos($this->getValue(TRUE), $classes);
        #var_dump($this->selector());
        #var_dump($pos);
        # Se não encontrar, ERRO!
        if ($pos === FALSE) {
            throw new \Exception("Can\'t find \$class ({$classes}).");
        } elseif ($pos === 0) {
            $before = trim(substr($this->getValue(TRUE), 0, $pos));
            $pos = count(preg_split('/\s+/', $before, -1, PREG_SPLIT_NO_EMPTY)) + count(preg_split('/\s+/', $classes, -1, PREG_SPLIT_NO_EMPTY));
            #var_dump($pos);
            # Pega do começo até a posição final do que procuramos
            $before = array_slice($this->value, 0, $pos);
            # Pega a partir da posição final do que procuramos
            $after = array_slice($this->value, $pos);

            # Junta tudo em um array
            $this->setValue(array_merge($before, $newClasses, $after));
        } else {
            # Pega as classes que irão aparecer antes das novas classes
            $before = trim(substr($this->getValue(TRUE), 0, $pos + strlen($classes)));
            # Calcula a posição no vetor baseado nas classes existentes
            $pos = count(preg_split('/\s+/', $before, -1, PREG_SPLIT_NO_EMPTY)) - 1;

            # Pega as classes precedentes
            $before = array_slice($this->value, 0, $pos + 1);
            # Pega as classes que virão após
            $after = array_slice($this->value, $pos + 1);

            # Junta tudo
            $this->setValue(array_merge($before, $newClasses, $after));
        }

        return $this;
    }

    public function removeClass($class) {
        foreach (Node::arrayFlatten(func_get_args()) as $class) {
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
        $remove = self::outputValue(...func_get_args());
        $current = $this->getValue();

        if (strlen($remove) > 0 AND is_int(strpos($current, $remove))) {
            $this->setValue(explode(' ', preg_replace("/{$remove}/", '', $current)));
        }

        return $this;
    }

    /**
     * Return classes that matches the parameters
     * @param string $classes as many as you want
     * @return string could be more than one class
     */
    public function hasAny($classes) {
        $matches = array();
        $classes = array_map('preg_quote', Node::arrayFlatten(func_get_args()));

        preg_match_all('/' . implode('|', $classes) . '/', $this->getValue(), $matches);

        return $matches[0];
    }

    public static function safeValue($value) {
        return preg_replace('/[^_a-zA-Z0-9\-]/', '', $value);
    }

}
