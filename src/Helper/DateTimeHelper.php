<?php

namespace App\Helper;

class DateTimeHelper
{
    CONST DB_DATETIME_FORMAT           = 'Y-m-d H:i:s';
    CONST DB_MONTH_END_DATETIME_FORMAT = 'Y-m-t 23:59:59';
    CONST DB_MONTH_END_DATE_FORMAT     = 'Y-m-t';
    CONST DEFAULT_DATE_FORMAT          = 'd.m.Y.';
    CONST DEFAULT_DATETIME_FORMAT      = 'd.m.Y. H:i';
    CONST DEFAULT_TIME_FORMAT          = 'H:i';
    CONST YEAR = 'Y';

    public const APP_TIMEZONE = 'Europe/Sarajevo';

    public static $defaultTimezone = 'UTC';
    public static $userTimezone    = 'Europe/Sarajevo';


    /**
     * @param string $value
     * @param $timezone
     * @return \DateTime
     * @throws \Exception
     */
    public static function getNew(string $value = 'now', $timezone = null, ): \DateTime
    {
        $timezone = $timezone ?? self::$defaultTimezone;

        $tz = new \DateTimeZone($timezone);
        $dateTime = new \DateTime($value, $tz);

        return $dateTime;
    }

    /**
     * @param string $value
     * @param $timezone
     * @param int $hoursToAdd
     * @return \DateTime
     * @throws \Exception
     */
    public static function getNewForSlot(string $value = 'now', $timezone = null, int $hoursToAdd = 0): \DateTime
    {
        $timezone = $timezone ?? self::$defaultTimezone;

        $tz = new \DateTimeZone($timezone);
        $dateTime = new \DateTime($value, $tz);

        if ($hoursToAdd > 0) {
            $dateTime->modify("+$hoursToAdd hours");
        }
        return $dateTime;
    }

    /**
     * @param string $value
     * @param $timezone
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public static function getNewForImmutable(string $value = 'now', $timezone = null): \DateTimeImmutable
    {
        $timezone = $timezone ?? self::APP_TIMEZONE;

        $tz = new \DateTimeZone($timezone);
        return new \DateTimeImmutable($value, $tz);
    }
/*
    public static function defaultTimeFormatInDefaultTimezone(\DateTime $dateTime): string
    {
        $dateTime->setTimezone(new \DateTimeZone(self::APP_TIMEZONE));
        return $dateTime->format(self::DEFAULT_TIME_FORMAT);
    }

    public static function defaultDateTimeFormatInDefaultTimezone(\DateTime $dateTime): string
    {
        $dateTime->setTimezone(new \DateTimeZone(self::APP_TIMEZONE));
        return $dateTime->format(self::DEFAULT_DATETIME_FORMAT);
    }*/

    /**
     * @param \DateTime $dateTime
     * @return \DateTime
     * @throws \Exception
     */
    public static function getNewUserTime(\DateTime $dateTime): \DateTime
    {
        return $dateTime->setTimezone(new \DateTimeZone(self::$userTimezone));
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public static function getNewUserTimeForImmutable(\DateTimeImmutable $dateTime): \DateTimeImmutable
    {
        return $dateTime->setTimezone(new \DateTimeZone(self::$userTimezone));
    }

    /*

    public static function getNewWithInterval($interval)
    {
        $now = self::getNew();
        $interval = new \DateInterval($interval);
        $now->add($interval);

        return $now;
    }

    public static function getNewForMatch($value = 'now', $timezone = null)
    {
        $now = self::getNew($value, $timezone);

        $newTime = self::getNew($now->format('Y-m-d H:' . 0));

        return $newTime;
    }

    public static function getMonthsBetweenTwoDates(\DateTime $startDate, \DateTime $endDate, $format = 'n', $keyFormat = "n")
    {
        $start = $startDate->modify('first day of this month');
        $end   = $endDate->modify('first day of next month');

        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        $ret = [];
        /** @var \DateTime $dt */
       /* foreach ($period as $dt) {
            $ret[$dt->format($keyFormat)] = $dt->format($format);
        }
        return $ret;
    }

    public static function format(\DateTime $dateTime, $format = 'Y-m-d H:i', $timezone = self::APP_TIMEZONE)
    {
        $dateTime->setTimezone(new \DateTimeZone($timezone));
        return $dateTime->format($format);
    }

    public static function build(\DateTime $dateTime, $format = 'd.m.Y. H:i')
    {
        return self::format($dateTime, $format);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \DateInterval
     */
   /* static public function diff(\DateTime $start, \DateTime $end)
    {
        return $start->diff($end);
    }

    public static function getNextYears($numberOfYears, $includePrevious = false)
    {
        $now = self::getNew();
        $year = intval($now->format('Y'));

        $ret = [
            $year
        ];

        for($i = 1; $i < $numberOfYears + 1; $i++) {
            $ret[] = $year + $i;
        }

        if($includePrevious) {
            $firstYear = $ret[0];
            $previousYear = $firstYear - 1;
            array_unshift($ret, $previousYear);
        }

        return $ret;
    }

    /** @return \DateTime */
   /* public static function firstDayOfMonth($value = 'now')
    {
        $now = self::getNew($value);
        return self::getNew($now->format('Y-m-1 00:00:01'));
    }

    public static function lastDayOfMonth($value = 'now')
    {
        $now = self::getNew($value);
        return self::getNew($now->format('Y-m-t 23:59:59'));
    }

    public static function checkNumber($number)
    {
        $number = intval($number);
        if($number < 10) {
            return '0' . $number;
        }
        return $number;
    }

    public static function getDateByDayNumberAndWeek($dayName, $week, $start = null, $end = null)
    {
        if(is_null($start)) {
            $start = '00:01';
        }
        if(is_null($end)) {
            $end = '23:59';
        }

        $startDate = self::getNew(sprintf('%s +%s Week %s', $dayName, $week, $start));
        $endDate = self::getNew(sprintf('%s +%s Week %s', $dayName, $week, $end));

        return [
            'start' => $startDate,
            'end'   => $endDate
        ];
    }

    public static function getHourMinutes($step = 1)
    {
        return range(0, 60, $step);
    }

    public static function getDatabaseFormat(\DateTime $dateTime)
    {
        return $dateTime->format(self::DB_DATETIME_FORMAT);
    }

    public static function convertToHoursAndMinutes($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return 0;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function convertToHoursFromMinutes($time)
    {
        if ($time < 1) {
            return 0;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);

        if($minutes > 0) {
            $minutes = $minutes / 60;
        }
        return $hours + $minutes;
    }

    public static function getFilterMonths()
    {
        $ret = [];
        for ($i = 1; $i < 13; $i++) {
            $ret[$i] = $i;
        }
        return $ret;
    }

    public static function getFilterYears()
    {
        $ret = [];
        for ($i = 2020; $i < 2025; $i++) {
            $ret[$i] = $i;
        }
        return $ret;
    }*/
}
