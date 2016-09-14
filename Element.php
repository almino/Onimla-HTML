<?php

namespace Onimla\HTML;

/**
 * An HTML element.
 *
 * @author AlminoMelo at gmail.com
 */
class Element extends Node implements HasAttribute, Appendable {

    private $parent = FALSE;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    protected $attr = array();

    /**
     * @var bool
     */
    private $selfClose;

    /**
     * Wether to show or not a comment identifying the element after the string
     * @var bool
     */
    private $commentSelector = FALSE;

    /**
     *
     * @var string
     */
    static private $htmlTrim = '&nbsp;|&#160;|&ensp;|&#8194;|&emsp;|&#8195;|&thinsp;|&#8201;';

    /**
     *
     * @param string $name required
     * @param
     */
    public function __construct($name, $attr = FALSE, $children = FALSE) {
        parent::__construct($children);
        $this->name = Attribute::name($name);

        if (is_array($attr) AND count($attr) > 0) {
            foreach ($attr as $name => $value) {
                $this->attr(new Attribute($name, $value));
            }
        }
    }

    public function __toString() {
        return $this->open() . $this->inner() . $this->close();
    }

    public function __clone() {
        $this->parent = is_object($this->parent) ? clone $this->parent : $this->parent;

        $new = array();

        foreach ($this->attr as $key => $attr) {
            if (is_object($attr)) {
                $new[$key] = clone $attr;
            } else {
                $new[$key] = $attr;
            }
        }


        $this->attr = $new;

        parent::__clone();
    }

    public function serialize() {
        return serialize(array(
            $this->before,
            $this->after,
            $this->children,
            $this->parent,
            $this->name,
            $this->attr,
            $this->selfClose,
            $this->commentSelector,
        ));
    }

    public function unserialize($serialized) {
        list(
                $this->before,
                $this->after,
                $this->children,
                $this->parent,
                $this->name,
                $this->attr,
                $this->selfClose,
                $this->commentSelector,
                ) = unserialize($serialized);
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        self::log('Setting parent for ' . get_class($this), TRUE);
        if (method_exists($parent, 'path')) {
            self::log("Parent for `{$this->name}{$this->selector()}` is `{$parent->path()}`");
        }

        $this->parent = $parent;

        if (method_exists($parent, 'path')) {
            self::log("Parent is {$parent->path()}");
            self::log("Child is {$this->path()}");
        }

        return $this;
    }

    /**
     * 
     * @param string $name
     * @return boolean|Attribute
     */
    public function getAttribute($name) {
        self::log('Asking for an attribute\'s value.');
        # Garantindo que o nome não tenha caracteres especiais
        $name = Attribute::name($name);

        if (key_exists($name, $this->attr)) {
            self::log("Attribute `{$name}` FOUND.");
            self::log('Attribute\'s value is ' . $this->attr[$name]->getValue());
            return $this->attr[$name];
        }

        self::log("Attribute `{$name}` NOT found.");
        return FALSE;
    }

    public function getAttributeValue($name) {
        if (!key_exists($name, $this->attr)) {
            return FALSE;
        }

        return $this->getAttribute($name)->getValue();
    }

    public function getAttributeVal($name) {
        return $this->getAttributeValue($name);
    }

    public function setAttributeValue($name, $value, $output = FALSE) {
        self::log('Setting an attribute\'s value with a string.');
        $attr = new Attribute($name, $value);
        self::log('Attribute successfuly created.', TRUE);
        $attr->setOutput($output);
        self::log('Output set.', TRUE);
        $this->attr[$attr->getName()] = $attr;

        return $this;
    }

    public function setAttributeVal($name, $value, $output = FALSE) {
        return $this->setAttributeValue($name, $value, $output);
    }

    public function addAttribute(Attribute $attr) {
        $this->attr[$attr->getName()] = $attr;
    }

    /**
     *
     * @param string|Attribute $name attribute name
     * @param string|Attribute $value attribute value
     * @param string|callable|Attribute $output Attribute::setOutput
     * @return boolean|Attribute|self
     */
    public function attr($name, $value = FALSE, $output = FALSE) {
        self::log('Getting or setting an attribute for ' . get_class($this), TRUE);

        $param1 = !$name instanceof Attribute AND strlen($name) > 0;
        $param2 = !$value instanceof Attribute AND strlen($value) > 0;
        $param3 = !$output instanceof Attribute AND strlen($output) > 0;

        self::log('Number of parameters passed: ' . func_num_args());
        self::log('Parameters passed: ' . var_export(func_get_args(), TRUE));

        # Está pedindo um atributo
        if (func_num_args() == 1 AND $param1) {
            return $this->getAttributeValue($name);
        } elseif ((func_num_args() == 2 OR func_num_args() == 3) AND ( $param1 AND $param2)) {
            if ($param2 === FALSE) {
                self::log('Second parameter is FALSE.');
                return $this->getAttributeValue($name);
            }

            $this->setAttributeValue($name, $value, $output);
        } elseif ((func_num_args() == 2 OR func_num_args() == 3) AND ( $param1 AND $value instanceof Attribute)) {
            self::log('Setting an attribute\'s value with an object.');
            $this->setAttributeValue($name, $value->getValue(), $output);
            /*
              $attr = new Attribute($name, $value->getValue());
              $attr->setOutput($output);
              $this->attr[$attr->getName()] = $attr;
             */
        } else {
            self::log('All of them are objects.', TRUE);
            foreach (self::arrayFlatten(func_get_args()) as $attr) {
                $this->addAttribute($attr);
            }
        }

        return $this;
    }

    public function hasAttr($name) {
        return $this->hasAttribute($name);
    }

    public function hasAttribute($name) {
        # Garantindo que o nome não tenha caracteres especiais
        $name = Attribute::name($name);

        return key_exists($name, $this->attr) AND $this->attr[$name] instanceof Attribute;
    }

    public final function &findByAttr($attr, $value) {
        $result = FALSE;

        self::log("Looking for [{$attr}=\"{$value}\"]");

        # Garantindo que o nome não tenha caracteres especiais
        $attr = Attribute::removeSpecialCharacters(
                        Attribute::convertAccentedCharacters(strtolower($attr)));


        $path = "{$this->path()}[{$attr}=\"{$value}\"]";


        # Vê se esse é o elemento procurado
        if ($this->attr($attr) AND $this->attr($attr) == $value) {
            self::log("{$this->path()} IS what you're looking for.");
            return $this;
        } else {
            self::log("{$this->path()} IS NOT what you're looking for.");
        }

        # Se não houver filhos, abandona a procura
        if (count($this->children) < 1) {
            self::log("No children for {$path}");
            return $result;
        }

        # Percorre os filhos
        foreach ($this->children as $child) {
            # Se o filho for um objeto OOHTML
            if (method_exists($child, 'attr') AND method_exists($child, 'findByAttr')) {
                # Vê se este filho é o procurado
                if ($child->attr($attr) == $value) {
                    if (method_exists($child, 'path')) {
                        self::log($child->path() . ' IS what you\'re looking for.');
                    }
                    # Retorna o filho encontrado
                    return $child;
                } else {
                    # Se este filho não é o procurado, pergunta aos filhos deste
                    $found = $child->findByAttr($attr, $value);
                    # Se tiver encontrado o filho procurado
                    if ($found !== FALSE) {
                        return $found;
                    }
                }
            } else {
                self::log('$child has no method `attr` OR `findByAttr`');
                self::log('$child = ' . serialize($child));
            }
        }

        #log_hr('info');
        # Caso nada dê certo
        return $result;
    }

    public function &findByName($value) {
        return $this->findByAttr('name', $value);
    }

    public function &findById($value) {
        self::log('Looking for #' . $value);
        return $this->findByAttr('id', $value);
    }

    /**
     * Find children which has the value passed in the second argument.
     * @param string $attr
     * @param string $regexOrValue Will be converted to an valid regular expression
     * @param integer $level
     * @return \Onimla\HTML\Node
     */
    public function matchAttribute($attr, $regexOrValue, $level = FALSE) {
        # Retornamos um nó de elementos
        $result = new Node;

        # Se não temos elementos filhos, retornamos o nó vazio
        if (!is_array($this->children) OR count($this->children) < 1) {
            return $result;
        }

        # O nível para buscar
        self::log('$level = ' . var_export($level, TRUE));
        # Garante que o nível é maior que ou igual a zero
        $level = ($level < 0) ? 0 : $level;

        self::log("Looking for `{$attr}` matching \"{$regexOrValue}\" in {$this->path()}'s children.");

        foreach ($this->children as &$child) {
            /* @var $child self */
            # Verifica se o elemento filho da vez tem o método `attr`
            if (is_object($child) AND method_exists($child, 'attr')) {
                # Pede o atributo.
                $attr = $child->attr($attr);
                # Verifica se o atributo foi encontrado
                if ($attr !== FALSE AND $attr->matchValue($regexOrValue)) {
                    self::log("Found an element matching \"{$regexOrValue}\": {$child->path()}");
                    # Se tive sido encontrado, coloca na coleção
                    $result->append($child);
                } else {
                    self::log("{$child->path()}[{$attr}] DOES NOT match `{$attr}` \"{$regexOrValue}\".");
                }

                # Se é para se aprofundar na árvore
                if ($level > 0 AND method_exists($child, __FUNCTION__)) {
                    $result->merge($child->matchAttribute($attr, $regexOrValue, $level--));
                } else {
                    self::log('Reached end of $level or $child has no method "' . __FUNCTION__ . '".');
                }
            }
        }

        return $result;
    }

    /**
     * 
     * @param string $attr
     * @param string $regexOrString
     * @param integer $level
     * @return \Onimla\HTML\Node
     */
    public function matchAttr($attr, $regexOrString, $level = FALSE) {
        return $this->matchAttribute($attr, $regexOrString, $level);
    }

    /**
     * 
     * @param string|array $classes Will be converted to an valid regular expression
     * @param integer $level
     * @return Attribute\Klass
     */
    public function matchClass($classes, $level = FALSE) {
        if (is_array($classes)) {
            $classes = '/'
                    . implode('|', array_map('preg_quote', Node::arrayFlatten($classes)))
                    . '/';
        }

        return $this->matchAttribute('class', $classes);
    }

    /**
     * 
     * @return Attribute\Klass
     */
    public function getClassAttribute() {
        $attrName = 'class';

        if (!key_exists($attrName, $this->attr)) {
            $this->attr(new Attribute\Klass());
        }

        return $this->getAttribute($attrName);
    }

    /**
     * Use as many parameters as you want
     * @param string $class
     * @return Element
     */
    public function addClass($class) {
        #require_once 'Attribute/Klass.class.php';

        $attrName = 'class';
        $values = array_filter(self::arrayFlatten(func_get_args()), 'strlen');

        if (count($values) < 1) {
            return $this;
        }

        if (!key_exists($attrName, $this->attr)) {
            $this->attr[$attrName] = new Attribute\Klass($values);
            return $this;
        }

        $this->attr[$attrName]->addValue(...$values);
        return $this;
    }

    public function removeClass($class) {
        #require_once 'Attribute/Klass.class.php';

        $attr = $this->getAttribute('class');

        if ($attr !== FALSE AND $attr instanceof Attribute\Klass) {
            call_user_func_array(array($attr, __FUNCTION__), func_get_args());
        }

        return $this;
    }

    /**
     * 
     * @param string $class Will be converted to an valid regular expression
     * @return boolean
     */
    public function hasClass($class) {
        return $this->getClassAttribute()->matchValue($class);
    }

    public function aria($property, $value = FALSE, $convert = 'encode') {
        return $this->attr('aria-' . $property, $value, $convert);
    }

    public function css($property, $value = FALSE) {
        #require_once 'Attribute/Style.class.php';

        $attrName = 'style';

        if (!key_exists($attrName, $this->attr)) {
            $this->attr[$attrName] = new Attribute\Style(...func_get_args());
            return $this;
        }

        call_user_func_array(array($this->attr[$attrName], 'addValue'), func_get_args());
        return $this;
    }

    /**
     * Store arbitrary data associated with the matched elements or return the
     * value at the named data store for the first element in the set of
     * matched elements.
     * @param string $key
     * @param string $value
     * @param string $output
     * @return Element
     */
    public function data($key, $value = FALSE, $output = 'encode') {
        if ($value === FALSE) {
            return $this->attr('data-' . $key);
        }

        #require_once 'Attribute/Data.class.php';

        $attr = new Attribute\Data($key, $value);
        $attr->setOutput($output);

        $this->attr($attr);
    }

    /**
     * @param string $value
     * @return Element|string
     */
    public function id($value = FALSE) {
        $attrName = __FUNCTION__;

        if ($value === FALSE) {
            return $this->getAttribute($attrName);
        }

        #require_once 'Attribute/Identifier.class.php';

        $this->attr(new Attribute\Identifier($value));
    }

    /**
     * Generate a unique ID attribute for an HTML Element
     * @link http://php.net/manual/en/function.uniqid.php
     * @param string $prefix [optional] <p>
     * Can be useful, for instance, if you generate identifiers
     * simultaneously on several hosts that might happen to generate the
     * identifier at the same microsecond.
     * </p>
     * <p>
     * With an empty <i>prefix</i>, the string will
     * be 13 characters long. If <i>more_entropy</i> is
     * <b>TRUE</b>, it will be 23 characters.
     * </p>
     * @param bool $more_entropy [optional] <p>
     * If set to <b>TRUE</b>, <b>uniqid</b> will add additional
     * entropy (using the combined linear congruential generator) at the end
     * of the return value, which increases the likelihood that the result
     * will be unique.
     * </p>
     * @return Element method chaining
     */
    public function uniqid($prefix = '', $more_entropy = FALSE) {
        $id = new Attribute\Identifier();
        $id->setUniqueValue($prefix, $more_entropy);
        return $this->attr($id);
    }

    function role($value = FALSE, $output = 'safe') {
        $attr = new Attribute(__FUNCTION__, $value);
        $attr->setOutput($output);

        return $this->attr($attr);
    }

    public function style($params = FALSE) {
        if ($params !== FALSE) {
            $params = implode('; ', self::arrayFlatten(func_get_args())) . ';';
        }

        return $this->attr('style', $params);
    }

    function title($text = FALSE) {
        if ($text === FALSE) {
            return $this->attr(__FUNCTION__);
        }

        #require_once 'Attribute/Title.class.php';

        $this->attr(new Attribute\Title($text));

        return $this;
    }

    public function removeAttr($name) {
        # Se houver atributos
        if (!empty($this->attr)) {
            # Para cada parâmetro passado para a função
            foreach (self::arrayFlatten(func_get_args()) as $attr) {
                # Garante que não há caracteres especiais no nome do atributo
                $attr = Attribute::name($attr);
                # Remove o atributo
                unset($this->attr[$attr]);
            }
        }

        return $this;
    }

    public function open() {
        $isSet = isset($this->selfClose);
        $isTrue = $this->selfClose === TRUE;
        $isFalse = $this->selfClose === FALSE;
        $hasChildren = $this->length() > 0;

        $s = "{$this->before}<{$this->name}";

        if (count($this->attr) > 0) {
            $s .= ' ';
            $s .= implode(' ', $this->attr);
        }

        if (
                (!$isSet AND ! $hasChildren)
                OR ( $isSet AND $isTrue)
        ) {
            $s .= ' /';
        }

        $s .= '>';

        if ($this->indentSource AND ( isset($this->selfClose) AND $this->selfClose === FALSE) AND $this->length() > 0) {
            $s .= PHP_EOL . self::TAB;
        } else {
            $s = (($this->indentSource AND ( (isset($this->selfClose) AND $this->selfClose === TRUE) OR $this->length() < 1)) ? self::TAB : '') . $s . $this->after;
        }

        return $s;
    }

    /**
     * Returns children as string
     * @return string
     */
    public function inner() {
        self::log('innerHTML called', TRUE);

        if ((isset($this->selfClose) AND $this->selfClose === TRUE) OR $this->length() < 1) {
            return '';
        }

        $glue = $this->indentSource ? PHP_EOL . self::TAB : '';

        return implode($glue, $this->children);

        /*
          $r = '';

          #self::log('Indent source: ' . var_export($this->indentSource, TRUE), TRUE);
          #self::log('Glue: ' . var_export($glue, TRUE), TRUE);

          if ($this->length() > 0) {
          foreach ($this->children as $child) {
          #var_dump(preg_split($pattern, htmlentities($child), -1, PREG_SPLIT_NO_EMPTY));
          /*
          if (!is_object($child)) {
          $r .= $glue;
          }
         *//*

          $r .= implode($glue, preg_split($pattern, $child, -1, PREG_SPLIT_NO_EMPTY));
          $r .= $this->indentSource ? PHP_EOL : '';

          if (!is_object($child) AND $child != $this->last()) {
          $r .= $this->indentSource ? self::TAB : '';
          }
          }
          }

          return $r;
         */
    }

    /**
     * Return the closing string for this element.
     * @return type
     */
    public function close() {
        $isSet = isset($this->selfClose);
        $isTrue = $this->selfClose === TRUE;
        $isFalse = $this->selfClose === FALSE;
        $hasChildren = $this->length() > 0;

        if (
                ($isSet AND $isTrue)
                OR ( !$isSet AND ! $hasChildren)
        ) {
            return '';
        }

        $s = "</{$this->name}>{$this->after}";

        if ($this->commentSelector AND strlen($this->selector()) > 0) {
            $s .= $this->indentSource ? PHP_EOL : '';
            $s .= '<!-- /' . $this->selector() . ' -->';
        }

        return $s;
    }

    public function before($child, $children) {
        $new_children = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($new_children);
        # Garante que não há várias dimensões no array
        $new_children = self::arrayFlatten($new_children);
        # Garante que os índices do array estão em ordem e são numéricos
        $this->children = array_values($this->children);

        $child_pos = array_search($child, $this->children);

        if ($child_pos === FALSE) {
            throw new \Exception('Can\'t find $child.');
        } else {
            $before = array_slice($this->children, 0, $child_pos);
            $after = array_slice($this->children, $child_pos);

            /*
              # DEBUG
              echo "\n<code>Before \$child:</code>\n<pre>";
              var_dump($before);
              echo "</pre>\n";
             */

            /*
              # DEBUG
              echo "\n<code>After \$child:</code>\n<pre>";
              var_dump($after);
              echo "</pre>\n";
             */
        }

        # Finalmente, junta tudo num úncio array
        $this->children = array_merge($before, $new_children, $after);

        return $this;
    }

    public function after($child, $children) {
        $new_children = func_get_args();
        # Remove o primeiro parâmetro passado para a função
        array_shift($new_children);
        # Garante que não há várias dimensões no array
        $new_children = self::arrayFlatten($new_children);
        # Garante que os índices do array estão em ordem e são numéricos
        $this->children = array_values($this->children);

        $child_pos = array_search($child, $this->children);

        if ($child_pos === FALSE) {
            throw new \Exception('Can\'t find $child.');
        } else {
            $before = array_slice($this->children, 0, $child_pos + 1);
            $after = array_slice($this->children, $child_pos + 1);

            /*
              # DEBUG
              echo "\n<code>Before \$child:</code>\n<pre>";
              var_dump($before);
              echo "</pre>\n";
             */

            /*
              # DEBUG
              echo "\n<code>After \$child:</code>\n<pre>";
              var_dump($after);
              echo "</pre>\n";
             */
        }

        # Finalmente, junta tudo num úncio array
        $this->children = array_merge($before, $new_children, $after);

        return $this;
    }

    public function append($children) {
        $children = self::filterChildren(func_get_args());

        self::log('Appending children to ' . get_class($this), FALSE);

        if (count($children) < 1) {
            self::log('Nothing to append.');
            return $this;
        }

        array_map(function($child) {
            if (is_object($child) AND method_exists($child, 'setParent')) {
                $child->setParent($this);
            } else {
                self::log('$child has no method `setParent`.');
                self::log('$child = ' . serialize($child));
            }
        }, $children);

        parent::append(...$children);

        return $this;
    }

    public function prepend($children) {
        $children = self::filterChildren(func_get_args());

        self::log('Prepending children to ' . get_class($this), TRUE);

        if (count($children) < 1) {
            self::log('Nothing to prepend.');
            return $this;
        }

        array_map(function($child) {
            if (is_object($child) AND method_exists($child, 'setParent')) {
                $child->setParent($this);
            } else {
                self::log('$child has no method `setParent`.');
                self::log('$child = ' . serialize($child));
            }
        }, $children);

        parent::prepend(...$children);

        return $this;
    }

    public function &find($name) {
        $name = Attribute::name($name);
        $r = array();

        if ($this->name == $name) {
            self::log("\"{$this->path()}\" is what you are looking for");
            return $this;
        } elseif (!empty($this->children)) {
            foreach ($this->children as $child) {
                if (method_exists($child, 'find')) {
                    $tmp = $child->find($name);
                    if (!empty($tmp)) {
                        self::log("Found a child at \"{$child->path()}\"");
                        $r[] = $tmp;
                    } else {
                        self::log("Not found at \"{$child->path()}\"");
                    }
                }
            }
        } elseif (empty($this->children)) {
            self::log("\"{$this->path()}\" has no children.");
        }

        switch (sizeof($r)) {
            case 1:
                reset($r);
                $r = current($r);
                return $r;
            default :
                return $r;
        }
    }

    public function appendText($text) {
        $text = array_filter(self::arrayFlatten(func_get_args()), 'strlen');
        foreach ($text as $t) {
            $this->append(htmlentities($t));
        }

        return $this;
    }

    /**
     * Use as many parameters as you want
     * Replaces children elements
     * @param string $text as many as you want
     * @return Element|string
     */
    public function text($text = FALSE) {
        self::log('Setting text for ' . get_class($this), TRUE);

        $text = array_filter(self::arrayFlatten(func_get_args()), 'strlen');
        self::log('Parameters passed: ' . count($text) . ' -- ' . print_r($text, TRUE));

        if (count($text) > 0) {
            $this->removeChildren();
            return $this->appendText($text);
        }

        self::log('About to return text.');
        return ($this->length() > 0) ? html_entity_decode(strip_tags($this->__toString())) : '';
    }

    public function ucfirst() {
        if ($this->countChildren() < 1) {
            return $this;
        }

        reset($this->children);

        $current = current($this->children);

        # Ignora os elementos vazios no início
        while ((is_object($current) AND method_exists($current, 'countChildren') AND $current->countChildren() < 1) AND strlen($current) < 1) {
            $current = next($this->children);
        }

        if (is_object($current) AND method_exists($current, __FUNCTION__)) {
            $current->ucfirst();
        } else {

            # Vê qual a melhor função
            $strtoupper = function_exists('mb_strtoupper') ? 'mb_strtoupper' : 'strtoupper';

            # Transforma a string em um vetor
            $pieces = str_split($current);

            # Coloca  primeira letra do vetor em maiúsculo
            $pieces[0] = $strtoupper($pieces[0]);

            # Transforma o vetor em string e atribui ao 
            $this->children[key($this->children)] = implode('', $pieces);
        }

        return $this;
    }

    public function strlen() {
        return strlen($this->text());
    }

    public function leftTrim() {
        # Pega o primeiro filho
        $first = $this->first();

        if ($first instanceof self) {
            # Se for um instancia dessa classe, chama o método dela
            $first->leftTrim();
        } elseif ($first !== FALSE) {
            # Redefine o primeiro filho
            $this->first(self::htmlLeftTrim($first));
            # Limpa os filhos que não tem conteúdo utilizável (NULL, FALSE ou strings vazias)
            $this->children = array_filter($this->children, 'strlen');
        }

        return $this;
    }

    public function rightTrim() {
        # Pega o último filho
        $last = $this->last();

        if ($last instanceof self) {
            # Se for um instancia dessa classe, chama o método dela
            $last->rightTrim();
        } elseif ($last !== FALSE) {
            $this->last(self::htmlRightTrim($last));
            # Limpa os filhos que não tem conteúdo utilizável (NULL, FALSE ou strings vazias)
            $this->children = array_filter($this->children, 'strlen');
        }

        #var_dump($this->children);

        return $this;
    }

    public function trim() {
        $this->leftTrim();
        return $this->rightTrim();
    }

    public static final function htmlLeftTrim($str) {
        return preg_replace('/^' . self::$htmlTrim . '+/', '', ltrim($str));
    }

    public static final function htmlRightTrim($str) {
        return preg_replace('/' . self::$htmlTrim . '+$/', '', rtrim($str));
    }

    /**
     * Remove HTML whitespaces entities &amp;nbsp;, &amp;ensp;, &amp;emsp;, &amp;thisp;…
     * And the traditional spaces removed by PHP's trim function " \t\n\r\0\x0B";
     * @param string $str
     * @return string
     */
    public static final function htmlTrim($str) {
        return self::htmlLeftTrim(self::htmlRightTrim($str));
    }

    public function selfClose($value = TRUE) {
        self::log('Setting closing tag for ' . __CLASS__, TRUE);

        if ($value) {
            self::log('There are no children for ' . __CLASS__);
            $this->removeChildren();
        }

        self::log('Self close is ' . var_export($value, TRUE));
        $this->selfClose = $value;
        return $this;
    }

    public static function index2id($array, $subject = '{index}') {
        foreach ($array as $key => $val) {
            $id = preg_replace('/\{index\}/', $key, $subject);
            if (is_object($val)) {
                if (is_callable(array($val, 'id'))) {
                    $val->id($id);
                } elseif (is_callable(array($val, 'attr'))) {
                    $val->attr('id', $id, 'safe');
                }
            }
        }
    }

    public function selector($filter = FALSE) {
        if ($filter == 'id') {
            return (($this->id() === FALSE) ? $this->uniqid()->id()->selector() : $this->id()->selector());
        }

        $result = NULL;

        if (count($this->attr) > 0) {
            # id
            $result .= (key_exists('id', $this->attr) ? $this->attr['id']->selector() : NULL);
            # classes
            $result .= ((key_exists('class', $this->attr) AND count($this->attr['class']) > 0) ?
                            $this->attr['class']->selector() : NULL);
        }

        return $result;
    }

    /**
     * Retunrs a string like parent > element#id.class.classes
     * @return string
     */
    public function path() {
        $parent = $this->getParent();

        if ($parent !== FALSE AND is_callable(array($parent, 'path'))) {
            $parent = $parent->path() . ' > ';
        }

        $result = $parent . $this->name;

        if (count($this->attr) > 1) {
            $result .= $this->selector();
        }

        return $result;
    }

    public function tidy() {
        if (!function_exists('tidy_parse_string') OR ! function_exists('tidy_get_body')) {
            return FALSE;
        }

        $tidy = tidy_parse_string($this, array(
            'indent' => TRUE,
            'indent-attributes' => TRUE,
                ), 'UTF8');

        return tidy_get_body($tidy)->child[0]->value;
    }

    public static function comment($instance, $string = FALSE) {
        if ($string === FALSE AND is_callable(array($instance, 'selector')) AND strlen($instance->selector())) {
            $string = '/' . $instance->selector();
        }

        return $instance . '<!-- ' . (($string !== FALSE) ? $string : 'Missing second parameter for ' . __METHOD__) . ' -->';
    }

    public function selectorToComment($set = TRUE) {
        $this->commentSelector = $set;

        return $this;
    }

}
