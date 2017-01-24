<?php

namespace mgcode\helpers;

use yii\base\InvalidParamException;

class TimeHelper
{
    /**
     * Returns time ago in words.
     * @param int $timestamp
     * @return string
     */
    public static function getTimeAgoInWord($timestamp)
    {
        $difference = time() - $timestamp;

        // Few seconds ago
        if ($difference < 15) {
            return \Yii::t('mgcode/helpers', 'Few seconds ago');
        } // Seconds ago
        else if ($difference < 60) {
            return \Yii::t('mgcode/helpers', '{0, plural, =1{one second ago} other{# seconds ago}}', $difference);
        } // Minutes ago
        else if ($difference < 60 * 60) {
            $minutes = round($difference / 60);
            return \Yii::t('mgcode/helpers', '{0, plural, =1{1 minute ago} other{# minutes ago}}', $minutes);
        } // Hours ago
        else if ($difference < 24 * 60 * 60) {
            $hours = round($difference / 60 / 60);
            return \Yii::t('mgcode/helpers', '{0, plural, =1{1 hour ago} other{# hours ago}}', $hours);
        } // Days ago
        else if ($difference < 7 * 24 * 60 * 60) {
            $days = round($difference / 24 / 60 / 60);
            return \Yii::t('mgcode/helpers', '{0, plural, =1{1 day ago} other{# days ago}}', $days);
        } // Weeks ago
        else if ($timestamp > strtotime('-1 month')) {
            $weeks = round($difference / 7 / 24 / 60 / 60);
            return \Yii::t('mgcode/helpers', '{0, plural, =1{1 week ago} other{# weeks ago}}', $weeks);
        } // Months ago
        else if ($timestamp > strtotime('-1 year')) {
            $interval = date_diff((new \DateTime(static::getTime($timestamp))), (new \DateTime()));
            return \Yii::t('mgcode/helpers', '{0, plural, =1{1 month ago} other{# months ago}}', $interval->m);
        }

        // Years ago
        $interval = date_diff((new \DateTime(static::getTime($timestamp))), (new \DateTime()));
        return \Yii::t('mgcode/helpers', '{0, plural, =1{1 year ago} other{# years ago}}', $interval->y);
    }

    /**
     * Checks whether currently is daytime
     * @param float $latitude
     * @param float $longitude
     * @static
     * @return bool
     */
    public static function getIsDaytime($latitude, $longitude)
    {
        $time = time();

        $sunrise = strtotime('today '.static::getSunriseTime($latitude, $longitude));
        $sunset = strtotime('today '.static::getSunsetTime($latitude, $longitude));

        if ($time >= $sunrise && $time <= $sunset) {
            return true;
        }

        return false;
    }

    /**
     * Returns sunrise time
     * @param float $latitude
     * @param float $longitude
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @param null|int $gmtOffset If not set, current GMT offset will be used.
     * @static
     * @return string
     */
    public static function getSunriseTime($latitude, $longitude, $timestamp = null, $gmtOffset = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        if ($gmtOffset === null) {
            $gmtOffset = self::getGmtOffset();
        }
        return date_sunrise($timestamp, SUNFUNCS_RET_STRING, $latitude, $longitude, ini_get('date.sunrise_zenith'), $gmtOffset);
    }

    /**
     * Returns sunset time
     * @param float $latitude
     * @param float $longitude
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @param null|int $gmtOffset If not set, current GMT offset will be used.
     * @static
     * @return string
     */
    public static function getSunsetTime($latitude, $longitude, $timestamp = null, $gmtOffset = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        if ($gmtOffset === null) {
            $gmtOffset = self::getGmtOffset();
        }
        return date_sunset($timestamp, SUNFUNCS_RET_STRING, $latitude, $longitude, ini_get('date.sunrise_zenith'), $gmtOffset);
    }

    /**
     * Calculates and returns current GMT offset
     * @return int
     */
    public static function getGmtOffset()
    {
        $gmtOffset = date('Z') / 60 / 60;
        $gmtOffset = round($gmtOffset);

        return $gmtOffset;
    }

    /**
     * Calculates daytime duration
     * @param float $latitude
     * @param float $longitude
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @static
     * @return int
     */
    public static function getDaytimeDuration($latitude, $longitude, $timestamp = null)
    {
        $startTime = strtotime('today '.self::getSunriseTime($latitude, $longitude, $timestamp));
        $endTime = strtotime('today '.self::getSunsetTime($latitude, $longitude, $timestamp));

        return $endTime - $startTime;
    }

    /**
     * Converts seconds to human readable format (H:i:s) string.
     * @param int $seconds
     * @param bool $hideSeconds
     * @static
     * @return string
     */
    public static function getHmsFromSeconds($duration, $hideSeconds = false)
    {
        list($hours, $minutes, $seconds) = static::getHmsParts($duration);
        $hm = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $hm .= ':'.str_pad($minutes, 2, "0", STR_PAD_LEFT);
        if (!$hideSeconds) {
            $hm .= ':'.str_pad($seconds, 2, "0", STR_PAD_LEFT);
        }
        return $hm;
    }

    /**
     * Converts duration in seconds to pretty human readable format (1h 12min 45s)
     * @param int $duration Duration in seconds
     * @param bool $hideSeconds
     * @return mixed
     */
    public static function getPrettyHms($duration, $hideSeconds = false)
    {
        $duration += 1;
        list($hours, $minutes, $seconds) = static::getHmsParts($duration);

        $labelParts = [];
        if ($hours) {
            $labelParts[] = $hours.'h';
        }
        if ($minutes) {
            $labelParts[] = $minutes.'min';
        }
        if ($seconds && !$hideSeconds) {
            $labelParts[] = $seconds.'sec';
        }
        return implode(' ', $labelParts);
    }

    /**
     * Divides duration in seconds into hours, minutes, seconds
     * @param int $duration
     * @return array
     */
    protected static function getHmsParts($duration)
    {
        $duration = intval($duration);
        $hours = intval($duration / 3600);
        $minutes = intval(($duration / 60) % 60);
        $seconds = intval($duration % 60);
        return [$hours, $minutes, $seconds];
    }

    /**
     * Parses seconds from time (H:i:s) string.
     * @param string $hms
     * @return bool
     */
    public static function getSecondsFromHms($hms)
    {
        $pattern = '/^(?<hours>[\d]{1,2})\:(?<minutes>[\d]{1,2})\:(?<seconds>[\d]{1,2})/';
        preg_match($pattern, $hms, $matches);
        if (!$matches) {
            return false;
        }

        $seconds = $matches['hours'] * 3600 + $matches['minutes'] * 60 + $matches['seconds'];
        return $seconds;
    }

    /**
     * Returns ISO8601 full datetime
     * E.g.: 2014-12-14T09:31:12+00:00
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @static
     * @return string
     */
    public static function getIso8601Date($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return date("c", $timestamp);
    }

    /**
     * Returns RFC 2822 formatted date.
     * Usually used in RSS feeds.
     * E.g.: Sun, 14 Dec 2014 09:39:45 +0000
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @static
     * @return string
     */
    public static function getRfc2822Date($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return date('r', $timestamp);
    }

    /**
     * Converts timestamp to numeric date (YYYYMMDD)
     * E.g.: 20141214
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @static
     * @return int
     */
    public static function getNumericDate($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return (int) date('Ymd', $timestamp);
    }

    /**
     * Converts timestamp to numeric month (YYYYMM)
     * E.g.: 201412
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @static
     * @return int
     */
    public static function getNumericMonth($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return (int) date('Ym', $timestamp);
    }

    /**
     * Return datetime.
     * Uses same format as mysql.
     * E.g.: 2014-12-14 09:46:01
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @return string
     */
    public static function getTime($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * Return current date
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @return string
     */
    public static function getDate($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return date("Y-m-d", $timestamp);
    }

    /**
     * Return formatted week number.
     * We use ISO-8601 standard, weeks starting on Monday.
     * E.g.: 2014-W52
     * @return string
     */
    public static function getWeek()
    {
        return date('o-\\WW');
    }

    /**
     * Return time difference between two timestamps with microseconds.
     * @param float $start
     * @param null|float $end If not set, current microtime will be used.
     * @return float Returns difference in seconds
     */
    public static function difference($start, $end = null)
    {
        if ($end === null) {
            $end = microtime(true);
        }

        $totalTime = ($end - $start);
        return $totalTime;
    }

    /**
     * Checks is given timestamp is today.
     * @param int $timestamp
     * @return boolean
     */
    public static function isToday($timestamp)
    {
        return date('Y-m-d', $timestamp) == date('Y-m-d');
    }

    /**
     * Checks is given timestamp is yesterday.
     * @param int $timestamp
     * @return boolean
     */
    public static function isYesterday($timestamp)
    {
        return date('Y-m-d', $timestamp) == date('Y-m-d', strtotime('yesterday'));
    }

    /**
     * Checks is given timestamp is in this week
     * We use ISO-8601 standard, weeks starting on Monday.
     * @param int $timestamp
     * @return boolean True if date is in this week
     */
    public static function isThisWeek($timestamp)
    {
        return date('o-\\WW', $timestamp) == date('o-\\WW');
    }

    /**
     * Checks is given timestamp is in this month
     * @param int $timestamp
     * @return boolean True if date is in this month
     */
    public static function isThisMonth($date)
    {
        return date('m Y', $date) == date('m Y', time());
    }

    /**
     * Checks is given timestamp is in this year
     * @param int $timestamp
     * @return boolean True if date is in this year
     */
    public static function isThisYear($timestamp)
    {
        return date('Y', $timestamp) == date('Y', time());
    }

    /**
     * Returns pretty weekday name.
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @param bool $useAlternateNames Whether to use today, tomorrow names.
     * @return string
     */
    public static function getPrettyWeekdayName($timestamp = null, $useAlternateNames = false)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        if ($useAlternateNames) {
            $date1 = new \DateTime(TimeHelper::getDate());
            $date2 = new \DateTime(TimeHelper::getDate($timestamp));
            $diff = $date2->diff($date1)->format("%a");

            if ($diff == 0) {
                return \Yii::t('mgcode/helpers', 'Today');
            } else if ($diff == 1) {
                return \Yii::t('mgcode/helpers', 'Tomorrow');
            }
        }

        $nr = date('N', $timestamp);
        return static::getPrettyWeekdayNameByNr($nr);
    }

    /**
     * Returns pretty weekday name by day numeric representation.
     * @param int $nr
     * @return string
     */
    public static function getPrettyWeekdayNameByNr($nr)
    {
        $translations = [
            0 => \Yii::t('mgcode/helpers', 'Sunday'),
            1 => \Yii::t('mgcode/helpers', 'Monday'),
            2 => \Yii::t('mgcode/helpers', 'Tuesday'),
            3 => \Yii::t('mgcode/helpers', 'Wednesday'),
            4 => \Yii::t('mgcode/helpers', 'Thursday'),
            5 => \Yii::t('mgcode/helpers', 'Friday'),
            6 => \Yii::t('mgcode/helpers', 'Saturday'),
            7 => \Yii::t('mgcode/helpers', 'Sunday'),
        ];
        return $translations[$nr];
    }

    /**
     * Returns pretty readable date.
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @return string
     */
    public static function getPrettyDate($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        $month = date('n', $timestamp);
        $params = [
            'day' => date('j', $timestamp),
        ];
        switch ($month) {
            case 1:
                return \Yii::t('mgcode/helpers', '{day} January', $params);
                break;
            case 2:
                return \Yii::t('mgcode/helpers', '{day} February', $params);
                break;
            case 3:
                return \Yii::t('mgcode/helpers', '{day} March', $params);
                break;
            case 4:
                return \Yii::t('mgcode/helpers', '{day} April', $params);
                break;
            case 5:
                return \Yii::t('mgcode/helpers', '{day} May', $params);
                break;
            case 6:
                return \Yii::t('mgcode/helpers', '{day} June', $params);
                break;
            case 7:
                return \Yii::t('mgcode/helpers', '{day} July', $params);
                break;
            case 8:
                return \Yii::t('mgcode/helpers', '{day} August', $params);
                break;
            case 9:
                return \Yii::t('mgcode/helpers', '{day} September', $params);
                break;
            case 10:
                return \Yii::t('mgcode/helpers', '{day} October', $params);
                break;
            case 11:
                return \Yii::t('mgcode/helpers', '{day} November', $params);
                break;
            case 12:
                return \Yii::t('mgcode/helpers', '{day} December', $params);
                break;
        }
    }

    /**
     * Returns pretty readable month name.
     * @param null|int $timestamp If not set, current timestamp will be used.
     * @return string
     */
    public static function getPrettyMonthName($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return \Yii::$app->formatter->asDate($timestamp, 'MMMM');
    }

    /**
     * Finds date gaps between given dates
     * @param $dates
     * @return array
     */
    public static function getMissingDates($dates)
    {
        if (count($dates) < 3) {
            return [];
        }
        $min = min($dates);
        $max = max($dates);

        $allDates = static::getDateRange($min, $max);
        $diff = array_diff($allDates, $dates);
        return array_values($diff);
    }

    /**
     * Returns dates between two dates.
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function getDateRange($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = (new \DateTime($endDate))->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);

        $result = [];
        foreach ($period as $date) {
            /** @var $date \DateTime */
            $result[] = $date->format('Y-m-d');
        }

        return $result;
    }

    /**
     * Validates date string
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function validate($date, $format)
    {
        $d = \DateTime::createFromFormat($format, (string) $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Converts date from one format into other.
     * @param string $date
     * @param string $fromFormat
     * @param string $toFormat
     * @return string
     */
    public static function convertDate($date, $fromFormat, $toFormat)
    {
        $d = \DateTime::createFromFormat($fromFormat, (string) $date);
        if (!$fromFormat) {
            throw new InvalidParamException('Date is incorrect.');
        }
        return $d->format($toFormat);
    }
}