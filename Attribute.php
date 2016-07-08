<?php

namespace Onimla\HTML;

/**
 * An HTML attribute.
 *
 * @author AlminoMelo at gmail.com
 */
class Attribute {

    protected $name;
    protected $value;
    protected $output;
    static $log = FALSE;

    public function __construct($name = FALSE, $value = FALSE) {
        /*
          # Log actions on a development enviroment
          self::$log = (ENVIRONMENT !== 'production');
         */

        if ($name !== FALSE) {
            $this->setName($name);
        }

        if ($value !== FALSE) {
            $this->setValue($value);
        }
    }

    public function __toString() {
        $value = $this->getValue(TRUE);

        if (strlen($value) < 1) {
            return $this->getName();
        }

        return "{$this->getName()}=\"{$value}\"";
    }

    public function selector() {
        return "[{$this}]";
    }

    public static function name($name) {
        return self::removeSpecialCharacters(self::convertAccentedCharacters($name));
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = self::name($name);
    }

    public function matchName($regexOrstring) {
        if (@preg_match($regexOrstring, null) === false) {
            $regexOrstring = "/" . preg_quote($regexOrstring) . "/";
        }

        return preg_match($regexOrstring, $this->getName());
    }

    public static function output($name, $value, $output) {
        if ($output === FALSE) {
            self::log('Simple output values as it is');
            # Por padrão, retorna o valor assim como ele é
            return $value;
        } elseif (is_callable($output)) {
            self::log('Output is callable.');
            # Caso seja enviada uma função que pode ser chamada
            return call_user_func($output, $value);
        } else {
            self::log('Output is a string.');
            # Se a saída for uma das strings previstas
            switch ($output) {
                case 'safe':
                    $value = self::removeSpecialCharacters(
                                    self::convertAccentedCharacters(strtolower($value)));
                    break;
                case 'html':
                case 'decode':
                    $value = html_entity_decode($value);
                    break;
                case 'encode':
                    $value = htmlentities($value);
                    break;
                case 'int':
                case 'numeric':
                case 'natural':
                    $value = intval($value);
                default:
                    $value = ($value === TRUE) ? $name : (string) $value;
            }

            return $value;
        }
    }

    /**
     * 
     * @param string|callable $output Possible values are safe, html, decode, encode, int, numeric, natural
     * @return mixed
     */
    public function getValue($output = TRUE) {
        if ($this->value instanceof self) {
            return $this->value->getValue($output);
        }

        if ($output === TRUE AND $this->getOutput() !== FALSE) {
            return self::output($this->getName(), $this->getValue(FALSE), $this->getOutput());
        }

        if ($output !== FALSE AND $output !== NULL AND is_callable($output)) {
            return self::output($this->getName(), $this->value, $output);
        }

        # Por padrão, retorna o valor assim como ele é
        return $this->value;
    }

    public function setValue($value) {
        self::log("new value for `{$this->getName()}` is " . var_export($value, TRUE), TRUE);

        $this->value = $value;
    }

    public function addValue($value) {
        $this->value .= $value;
    }

    public function matchValue($regexOrstring) {
        if (@preg_match($regexOrstring, NULL) === FALSE) {
            $regexOrstring = "/" . preg_quote($regexOrstring) . "/";
        }

        return preg_match($regexOrstring, $this->getValue(TRUE));
    }

    public function value($value = FALSE) {
        if ($value === FALSE) {
            return $this->getValue();
        }

        $this->setValue($value);

        return $this;
    }

    public function val($value = FALSE) {
        return $this->val($value);
    }

    /**
     * Return if the value is set.
     * @return boolean
     */
    public function isValueSet() {
        return strlen($this->getValue()) > 0;
    }

    public function getOutput() {
        return $this->output;
    }

    /**
     * You can set anything callable
     * @param string|callable $output Possible values are safe, html, decode, encode, int, numeric, natural
     */
    public function setOutput($output) {
        $this->output = $output;
    }

    public static function removeSpecialCharacters($string) {
        $strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';

        /* http://www.w3.org/TR/2008/REC-xml-20081126/#NT-Name */
        return preg_replace('/[^:A-Z_a-z\-\.^\d^\[^\]]/u', '', $strtolower($string));
    }

    /**
     * Convert Special Chars
     *
     * Found Via:
     * http://us.php.net/manual/en/function.chr.php#72145
     *
     * @access    public
     * @param    string    the string
     * @return    string
     */
    public static function convertAccentedCharacters($str) {
        if (function_exists('convert_accented_characters')) {
            return convert_accented_characters($str);
        }

        $foreignCharacters = array(
            '/ä|æ|ǽ/' => 'ae',
            '/ö|œ/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|Α|Ά|Ả|Ạ|Ầ|Ẫ|Ẩ|Ậ|Ằ|Ắ|Ẵ|Ẳ|Ặ|А/' => 'A',
            '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|α|ά|ả|ạ|ầ|ấ|ẫ|ẩ|ậ|ằ|ắ|ẵ|ẳ|ặ|а/' => 'a',
            '/Б/' => 'B',
            '/б/' => 'b',
            '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
            '/ç|ć|ĉ|ċ|č/' => 'c',
            '/Д/' => 'D',
            '/д/' => 'd',
            '/Ð|Ď|Đ|Δ/' => 'Dj',
            '/ð|ď|đ|δ/' => 'dj',
            '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ε|Έ|Ẽ|Ẻ|Ẹ|Ề|Ế|Ễ|Ể|Ệ|Е|Э/' => 'E',
            '/è|é|ê|ë|ē|ĕ|ė|ę|ě|έ|ε|ẽ|ẻ|ẹ|ề|ế|ễ|ể|ệ|е|э/' => 'e',
            '/Ф/' => 'F',
            '/ф/' => 'f',
            '/Ĝ|Ğ|Ġ|Ģ|Γ|Г|Ґ/' => 'G',
            '/ĝ|ğ|ġ|ģ|γ|г|ґ/' => 'g',
            '/Ĥ|Ħ/' => 'H',
            '/ĥ|ħ/' => 'h',
            '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Η|Ή|Ί|Ι|Ϊ|Ỉ|Ị|И|Ы/' => 'I',
            '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|η|ή|ί|ι|ϊ|ỉ|ị|и|ы|ї/' => 'i',
            '/Ĵ/' => 'J',
            '/ĵ/' => 'j',
            '/Ķ|Κ|К/' => 'K',
            '/ķ|κ|к/' => 'k',
            '/Ĺ|Ļ|Ľ|Ŀ|Ł|Λ|Л/' => 'L',
            '/ĺ|ļ|ľ|ŀ|ł|λ|л/' => 'l',
            '/М/' => 'M',
            '/м/' => 'm',
            '/Ñ|Ń|Ņ|Ň|Ν|Н/' => 'N',
            '/ñ|ń|ņ|ň|ŉ|ν|н/' => 'n',
            '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ο|Ό|Ω|Ώ|Ỏ|Ọ|Ồ|Ố|Ỗ|Ổ|Ộ|Ờ|Ớ|Ỡ|Ở|Ợ|О/' => 'O',
            '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ο|ό|ω|ώ|ỏ|ọ|ồ|ố|ỗ|ổ|ộ|ờ|ớ|ỡ|ở|ợ|о/' => 'o',
            '/П/' => 'P',
            '/п/' => 'p',
            '/Ŕ|Ŗ|Ř|Ρ|Р/' => 'R',
            '/ŕ|ŗ|ř|ρ|р/' => 'r',
            '/Ś|Ŝ|Ş|Ș|Š|Σ|С/' => 'S',
            '/ś|ŝ|ş|ș|š|ſ|σ|ς|с/' => 's',
            '/Ț|Ţ|Ť|Ŧ|τ|Т/' => 'T',
            '/ț|ţ|ť|ŧ|т/' => 't',
            '/Þ|þ/' => 'th',
            '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ũ|Ủ|Ụ|Ừ|Ứ|Ữ|Ử|Ự|У/' => 'U',
            '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|υ|ύ|ϋ|ủ|ụ|ừ|ứ|ữ|ử|ự|у/' => 'u',
            '/Ý|Ÿ|Ŷ|Υ|Ύ|Ϋ|Ỳ|Ỹ|Ỷ|Ỵ|Й/' => 'Y',
            '/ý|ÿ|ŷ|ỳ|ỹ|ỷ|ỵ|й/' => 'y',
            '/В/' => 'V',
            '/в/' => 'v',
            '/Ŵ/' => 'W',
            '/ŵ/' => 'w',
            '/Ź|Ż|Ž|Ζ|З/' => 'Z',
            '/ź|ż|ž|ζ|з/' => 'z',
            '/Æ|Ǽ/' => 'AE',
            '/ß/' => 'ss',
            '/Ĳ/' => 'IJ',
            '/ĳ/' => 'ij',
            '/Œ/' => 'OE',
            '/ƒ/' => 'f',
            '/ξ/' => 'ks',
            '/π/' => 'p',
            '/β/' => 'v',
            '/μ/' => 'm',
            '/ψ/' => 'ps',
            '/Ё/' => 'Yo',
            '/ё/' => 'yo',
            '/Є/' => 'Ye',
            '/є/' => 'ye',
            '/Ї/' => 'Yi',
            '/Ж/' => 'Zh',
            '/ж/' => 'zh',
            '/Х/' => 'Kh',
            '/х/' => 'kh',
            '/Ц/' => 'Ts',
            '/ц/' => 'ts',
            '/Ч/' => 'Ch',
            '/ч/' => 'ch',
            '/Ш/' => 'Sh',
            '/ш/' => 'sh',
            '/Щ/' => 'Shch',
            '/щ/' => 'shch',
            '/Ъ|ъ|Ь|ь/' => '',
            '/Ю/' => 'Yu',
            '/ю/' => 'yu',
            '/Я/' => 'Ya',
            '/я/' => 'ya'
        );

        $from = array_keys($foreignCharacters);
        $to = array_values($foreignCharacters);

        return preg_replace($from, $to, $str);
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

}
