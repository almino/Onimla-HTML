<?php

namespace Onimla\HTML;

/*
require_once 'Node.class.php';
require_once 'BrazilTime.class.php';
*/

/**
 * @property Span $container
 */
class BrazilDateInterval extends Node {

    const MACHINE_DATE = 'Y-m-d';
    const MACHINE_TIME = 'H:i:s';

    /**
     * @link http://php.net/manual/en/timezones.php Time zones
     * @var string
     */
    public static $timeZone = 'America/Fortaleza';
    public static $separatorTo = ' até ';
    public static $beforeIni = 'de ';
    public static $beforeEnd = 'até ';
    public static $beforeHour;
    public static $separatorTime = ' às ';
    public static $separatorTimeFirst = ' do dia ';
    public static $separatorTimes = ' a ';
    public static $afterIni;
    public static $separatorSameDay = ', ';
    public static $separatorSameMonthAnd = ' e ';
    public static $separatorSameMonthTo = ' a ';
    public static $betweenDateAndTime = ', ';
    public static $betweenDates = ' a ';
    public static $aterEnd = '.';
    public static $iniDateClass = 'begin-date date initial-date start-date';
    public static $endDateClass = 'date end-date';
    public static $iniTimeClass = 'begin-time initial-time start-time time';
    public static $endTimeClass = 'end-time time';
    public $spellSmallNumbers = FALSE;

    /**
     * @var \DateTime
     */
    protected $ini;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @var \DateInterval
     */
    protected $interval;

    public function __construct($ini, $end, $container = FALSE) {
        $this->ini($ini);
        $this->end($end);

        $this->container($container);

        $this->container->selectorToComment(TRUE);
    }

    public function __toString() {
        if ($this->container->length() < 1) {
            return (string) $this->prepare();
        }
        
        return (string) parent::__toString();
    }

    
    public function prepare() {
        self::log('Preparando os elementos filhos.', TRUE);

        $this->interval = $this->ini->diff($this->end);

        self::log('$ini = ' . var_export($this->ini->format(DATE_W3C), TRUE));
        $ini = new BrazilTime($this->ini->format(DATE_W3C));
        
        self::log('$end = ' . var_export($this->end->format(DATE_W3C), TRUE));
        $end = new BrazilTime($this->end->format(DATE_W3C));
        self::log('Criou os elementos filhos.');

        self::log('Definindo as classes.');
        $ini->addClass('begins', 'ini', 'init', 'inits', 'initial', 'start', 'starts');
        $end->addClass('end', 'ends');
        self::log('Defini as classes.');

        $this->container->removeChildren();

        if ($this->ini->format(BrazilTime::$year_format) == $this->end->format(BrazilTime::$year_format)) {
            # same year
            if ($this->ini->format(BrazilTime::$year_format) == date(BrazilTime::$year_format)) {
                # year is now
                $this->container->addClass('year-now');

                if (intval($this->ini->format('n')) == intval($this->end->format('n'))) {
                    # same month
                    if (intval($this->ini->format('j')) == intval($this->end->format('j'))) {
                        # same day
                        $this->container->addClass('same-day', 'equal-day', 'day-equal');
                        $ini->append($this->iniDate('date_medium'));
                        $this->container->append($ini);

                        if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                            # all day long
                            $this->container->addClass('all-day', 'all-day-long', 'full-day');
                            $this->container->append($end->open(), $end->close());
                        } else {
                            # same day, but different time
                            $ini->append($this->separatorSameDay());
                            $ini->append($this->beforeHour());
                            $ini->append($this->iniTime('time_short'));

                            $end->append($this->endTime('time_short'));

                            $this->container->append($this->separatorTime());
                            $this->container->append($end);
                        }
                    } else {
                        # same month, different day
                        $this->container->addClass('different-day', 'day-different');

                        if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                            # same month, different day, all day long
                            $this->container->addClass('all-day', 'all-day-long', 'full-day');

                            $day = $this->iniDate('date_short');
                            $day->removeChildren();
                            $day->append($this->spanDay($this->ini));

                            $ini->append($day);

                            $end->append($this->endDate());

                            $this->container->append($ini);
                            $this->container->append($this->separatorSameMonth());
                            $this->container->append($end);
                        } else {
                            # same month, different day, different time
                            $this->container->addClass('different-time', 'time-different');
                            /*
                              $day = $this->iniDate('date_short');
                              $day->text(BrazilTime::spellSmallNumbers($this->ini->format('j')));
                             */

                            #$ini->append($day);
                            $ini->append($this->iniTime('time_short'));
                            $ini->append($this->separatorTimeFirst());
                            $ini->append($this->iniDate('date_medium'));

                            $end->append($this->endTime('time_short'));
                            $end->append($this->separatorTimeFirst());
                            $end->append($this->endDate('date_medium'));

                            $this->container->append($ini);
                            $this->container->append($this->separatorTo());
                            $this->container->append($end);
                        }
                    }
                } else {
                    # year is now, different month
                    $this->container->addClass('different-day', 'different-month');

                    if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                        # year is now, different month, different day, all day long
                        $ini->append($this->iniDate('date_medium'));

                        $end->append($this->endDate('date_medium'));

                        $this->container->append($ini);
                        $this->container->append($this->betweenDates());
                        $this->container->append($end);
                    } else {
                        # year is now, different month, different day, different time
                        $this->container->addClass('different-time', 'time-different');

                        $ini->append($this->iniTime('time_short'));
                        $ini->append($this->separatorTimeFirst());
                        $ini->append($this->iniDate('date_medium'));

                        $end->append($this->endTime('time_short'));
                        $end->append($this->separatorTimeFirst());
                        $end->append($this->endDate('date_medium'));

                        $this->container->append($ini);
                        $this->container->append($this->separatorTo());
                        $this->container->append($end);
                    }
                }
            } else {
                # year IS NOT now
                if (intval($this->ini->format('n')) == intval($this->end->format('n'))) {
                    # same month
                    if (intval($this->ini->format('j')) == intval($this->end->format('j'))) {
                        # same day
                        $this->container->addClass('same-day', 'equal-day', 'day-equal');
                        $ini->append($this->iniDate('date_long'));
                        $this->container->append($ini);

                        if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                            # all day long
                            $this->container->addClass('all-day', 'all-day-long', 'full-day');
                            $this->container->append($end->open(), $end->close());
                        } else {
                            # same day, different time
                            $ini->append($this->separatorSameDay());
                            $ini->append($this->beforeHour());
                            $ini->append($this->iniTime('time_short'));

                            $end->append($this->endTime('time_short'));

                            $this->container->append($this->separatorTime());
                            $this->container->append($end);
                        }
                    } else {
                        # same month, different day
                        $this->container->addClass('different-day', 'day-different');

                        if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                            # same month, different day, all day long
                            $this->container->addClass('all-day', 'all-day-long', 'full-day');

                            $day = $this->iniDate('date_short');
                            $day->removeChildren();
                            $day->append($this->spanDay($this->ini));

                            $ini->append($day);

                            $end->append($this->endDate('date_long'));

                            $this->container->append($ini);
                            $this->container->append($this->separatorSameMonth());
                            $this->container->append($end);
                        } else {
                            # same month, different day, different time
                            $this->container->addClass('different-time', 'time-different');
                            /*
                              $day = $this->iniDate('date_short');
                              $day->text(BrazilTime::spellSmallNumbers($this->ini->format('j')));
                             */

                            #$ini->append($day);
                            $ini->append($this->iniDate('date_short'));
                            $ini->append($this->separatorTime());
                            $ini->append($this->iniTime('time_short'));

                            $end->append($this->endDate('date_short'));
                            $end->append($this->separatorTime());
                            $end->append($this->endTime('time_short'));

                            $this->container->append($ini);
                            $this->container->append($this->separatorTo());
                            $this->container->append($end);
                        }
                    }
                } else {
                    # year IS NOT now, different month
                    $this->container->addClass('different-day', 'different-month');

                    if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                        # year is now, different month, different day, all day long
                        $ini->append($this->iniDate('date_short'));

                        $end->append($this->endDate('date_short'));

                        $this->container->append($ini);
                        $this->container->append($this->betweenDates());
                        $this->container->append($end);
                    } else {
                        # year IS NOT now, different month, different day, different time
                        $this->container->addClass('different-time', 'time-different');
                        $ini->append($this->iniDate('date_short'));
                        $ini->append($this->separatorTime());
                        $ini->append($this->iniTime('time_short'));

                        $end->append($this->endDate('date_short'));
                        $end->append($this->separatorTime());
                        $end->append($this->endTime('time_short'));

                        $this->container->append($ini);
                        $this->container->append($this->separatorTo());
                        $this->container->append($end);
                    }
                }
            }
        } else {
            self::log('Different year');
            $this->container->addClass('different-year', 'time-year');
            if (strpos($this->ini->format(DATE_W3C), 'T00:0') !== FALSE AND strpos($this->end->format(DATE_W3C), 'T23:5') !== FALSE) {
                # different year, different month, different day, all day long
                $this->container->addClass('all-day', 'all-day-long', 'full-day');

                $ini->append($this->iniDate('date_short'));

                $end->append($this->endDate('date_short'));

                $this->container->append($ini);
                $this->container->append($this->betweenDates());
                $this->container->append($end);
            } else {
                # different year, different month, different day, different time
                $this->container->addClass('different-time', 'time-different');

                $ini->append($this->iniDate('date_short'));
                $ini->append($this->separatorTime());
                $ini->append($this->iniTime('time_short'));

                $end->append($this->endDate('date_short'));
                $end->append($this->separatorTime());
                $end->append($this->endTime('time_short'));

                $this->container->append($ini);
                $this->container->append($this->separatorTo());
                $this->container->append($end);
            }
        }

        return $this;
    }

    public function ini($datetime = FALSE) {
        if ($datetime === FALSE) {
            return $this->ini;
        }

        if ($datetime instanceof \DateTime) {
            $this->ini = $datetime;
        } else {
            $this->ini = new \DateTime($datetime);
            $this->ini->setTimezone(new \DateTimeZone(self::$timeZone));
        }
    }

    public function end($datetime = FALSE) {
        if ($datetime === FALSE) {
            return $this->end;
        }

        if ($datetime instanceof \DateTime) {
            $this->end = $datetime;
        } else {
            $this->end = new \DateTime($datetime);
            $this->end->setTimezone(new \DateTimeZone(self::$timeZone));
        }
    }

    public function container($container = FALSE) {
        $classes = array(
            'DatePeriod',
            'date-period',
            'DateInterval',
            'date-interval',
            'DateTimePeriod',
            'datetime-period',
            'date-time-period',
            'DateTimeInterval',
            'datetime-interval',
            'date-time-interval',
        );

        if (!isset($this->container)) {
            require_once 'Span.class.php';
            $this->container = new Span(FALSE, $classes);
        }

        if ($container === FALSE) {
            return $this->container;
        }

        if ($container instanceof Element) {
            $this->container = $container;
        } else {
            $this->container = new Element($container);
            $this->container->addClass($classes);
        }
    }

    protected function span(\DateTime $datetime, $format) {
        $time = new BrazilTime($datetime->format(DATE_W3C));
        $span = new Span($time->getFormattedString($format, $this->spellSmallNumbers));

        $span->addClass($format, str_replace('_', '-', $format));

        return $span;
    }

    protected function spanDate($datetime, $format = 'date_medium') {
        $span = $this->span($datetime, $format);

        $span->data('value', $datetime->format(self::MACHINE_DATE));

        if ($format == 'date_short') {
            $span->title($this->spanDate($datetime, 'date_long')->text(), 'none');
        }

        return $span;
    }

    protected function spanTime($datetime, $format = 'time_short') {
        $span = $this->span($datetime, $format);

        $span->data('value', $datetime->format(self::MACHINE_TIME));

        return $span;
    }

    protected function spanDay(\DateTime $datetime, $format = 'time_short') {
        $span = new Span(BrazilTime::spellSmallNumbers($datetime->format('j')), 'day day-spelled number-spelled');

        $span->data('value', $datetime->format('j'));

        return $span;
    }

    protected function endDate($format = 'date_medium') {
        $span = $this->spanDate($this->end, $format);
        $span->addClass(self::$endDateClass);
        return $span;
    }

    protected function iniDate($format = 'date_medium') {
        $span = $this->spanDate($this->ini, $format);
        $span->addClass(self::$iniDateClass);

        return $span;
    }

    protected function endTime($format = 'time_short') {
        $span = $this->spanTime($this->end, $format);
        return $span;
    }

    protected function iniTime($format = 'time_short') {
        $span = $this->spanTime($this->ini, $format);
        $span->addClass(self::$iniTimeClass);
        return $span;
    }

    protected function separatorTo() {
        return new Span(self::$separatorTo, 'date-time-separator between-date-time');
    }

    protected function separatorTime() {
        return new Span(self::$separatorTime, 'time-separator between-time');
    }

    protected function separatorTimeFirst() {
        return new Span(self::$separatorTimeFirst, 'time-separator before-date');
    }

    protected function separatorTimes() {
        return new Span(self::$separatorTimes, 'time-separator between-time');
    }

    protected function separatorSameDay() {
        return new Span(self::$separatorSameDay, 'date-separator separator');
    }

    protected function separatorSameMonth() {
        $span = new Span(FALSE, 'date-separator separator');

        if (intval($this->ini->format('j')) + 1 == intval($this->end->format('j'))) {
            $span->addClass('one-day-different', 'separator-and');
            $span->text(self::$separatorSameMonthAnd);
        } else {
            $span->addClass('days-different', 'separator-to');
            $span->text(self::$separatorSameMonthTo);
        }

        return $span;
    }

    protected function betweenDateAndTime() {
        return new Span(self::$betweenDateAndTime, 'date-time-separator separator');
    }

    protected function betweenDates() {
        return new Span(self::$betweenDates, 'date-separator time-separator separator');
    }

    protected function beforeHour() {
        return new Span(self::$beforeHour, 'before-hour');
    }

    public function ucfirst() {
        $this->container->ucfirst();
        return $this;
    }

}
