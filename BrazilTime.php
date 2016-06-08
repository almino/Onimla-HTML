<?php

namespace Onimla\HTML;

#require_once 'Time.class.php';

class BrazilTime extends Time {

    public $date_format = 'long';
    public $time_format = 'short';
    public static $date_long = '{d} de {month_name} de {year}';
    public static $date_medium = '{d} de {month_name}';
    public static $date_short = '{dd}/{month}/{year}';
    public static $time_long = '{h} h {min} min {sec} s';
    public static $time_short = '{hh}h{min}min';
    public static $midnight = ' à meia-noite';
    public static $d_format = 'j';
    public static $dd_format = 'd';
    public static $month_format = 'm';
    public static $year_format = 'Y';
    public static $h_format = 'G';
    public static $hh_format = 'H';
    public static $min_format = 'i';
    public static $sec_format = 's';
    public static $separator = ' às ';

    public function __construct($datetime) {
        parent::__construct(FALSE, $datetime);
    }

    public function __toString() {
        if ($this->length() < 1) {
            $dt = new \DateTime($this->dateTime()->getValue());
            $date_format = 'date_' . $this->date_format;
            $time_format = 'time_' . $this->time_format;

            $result = str_replace(array(
                '{d}',
                '{dd}',
                '{month}',
                '{month_name}',
                '{year}',
                    ), array(
                $dt->format(self::$d_format),
                $dt->format(self::$dd_format),
                $dt->format(self::$month_format),
                htmlentities(self::month($dt->format('n'))),
                $dt->format(self::$year_format),
                    ), self::$$date_format);

            if (intval($dt->format('G')) > 0) {
                $result .= htmlentities(self::$separator);
                $time_format = self::$$time_format;

                if ($this->time_format == 'short' AND intval($dt->format('i')) === 0) {
                    $time_format = substr($time_format, 0, strpos($time_format, '{min}'));
                }

                $result .= str_replace(array(
                    '{h}',
                    '{hh}',
                    '{min}',
                    '{sec}',
                        ), array(
                    $dt->format(self::$h_format),
                    $dt->format(self::$hh_format),
                    $dt->format(self::$min_format),
                    $dt->format(self::$sec_format),
                        ), $time_format);
            } else {
                $result .= self::$midnight;
            }

            return $this->open() . $result . $this->close();
        }

        return parent::__toString();
    }

    public function setDateFormat($value) {
        $this->date_format = $value;
    }

    public function getFormattedString($format, $spell = FALSE) {
        $dt = new \DateTime($this->dateTime()->getValue());

        if (strpos($format, 'date_') === 0) {
            return str_replace(array(
                '{d}',
                '{dd}',
                '{month}',
                '{month_name}',
                '{year}',
                    ), array(
                $spell ? self::spellSmallNumbers($dt->format(self::$d_format)) : $dt->format(self::$d_format),
                $dt->format(self::$dd_format),
                $dt->format(self::$month_format),
                self::month($dt->format('n')),
                $dt->format(self::$year_format),
                    ), self::$$format);
        }

        if (strpos($format, 'time_') === 0) {
            # Se não for meia noite
            if (intval($dt->format('G')) > 0) {
                $time_format = self::$$format;

                # Se o formato for curto e minutos igual a zero
                if ($this->time_format == 'short' AND intval($dt->format('i')) === 0) {
                    $time_format = substr($time_format, 0, strpos($time_format, '{min}'));
                }

                return str_replace(array(
                    '{h}',
                    '{hh}',
                    '{min}',
                    '{sec}',
                        ), array(
                    $dt->format(self::$h_format),
                    $dt->format(self::$hh_format),
                    $dt->format(self::$min_format),
                    $dt->format(self::$sec_format),
                        ), $time_format);
            } else {
                return substr(self::$midnight, 4);
            }
        }
    }

    public static function month($index) {
        $month_name = array(
            1 => 'janeiro',
            2 => 'fevereiro',
            3 => 'março',
            4 => 'abril',
            5 => 'maio',
            6 => 'junho',
            7 => 'julho',
            8 => 'agosto',
            9 => 'setembro',
            10 => 'outubro',
            11 => 'novembro',
            12 => 'dezembro',
        );

        return $month_name[$index];
    }

    /**
     * @param string $number 
     */
    public static function spellSmallNumbers($number) {
        $number = preg_replace('/[^\d]/', '', $number);

        if (intval($number) < 21) {
            $spelled = explode(',', 'zero,primeiro,dois,três,quatro,cinco,seis,'
                    . 'sete,oito,nove,dez,onze,doze,treze,quatorze,quinze,'
                    . 'dezesseis,dezesete,dezoito,dezenove,vinte');
            return $spelled[$number];
        }

        return $number;
    }

}
