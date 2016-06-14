<?php
namespace mgcode\helpers;

class NumberHelper
{
    /**
     * Prepends leading zeros to number
     * @param string $value
     * @param int $length
     * @return string
     */
    public static function leadingZeros($value, $length)
    {
        return str_pad($value, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Returns random unique integer ID as string
     * Datatype for database is BIGINT(20)
     * Description:
     * unix timestamp without first digit
     * plus
     * 1/1000 of second (always 4 digits)
     * plus
     * random digit in range from 100000 to 999999
     * @return string
     */
    public static function getGuid()
    {
        list($usec, $sec) = explode(' ', microtime());
        $sec = substr($sec, 1, 9);
        $usec = substr($usec, 2, 4);
        $guid = $sec.$usec.mt_rand(100000, 999999);

        return $guid;
    }

    /**
     * Returns the next highest float value by rounding up value if necessary.
     * @param float $number
     * @param int $decimals
     * @return float
     */
    public static function ceil($number, $decimals = 0)
    {
        $x = str_pad('1', 1 + $decimals, '0', STR_PAD_RIGHT);
        $result = ceil($number * $x) / $x;

        // Fix decimal places precision
        return round($result, $decimals);
    }

    /**
     * Returns the next lowest float value by rounding down value if necessary.
     * @param float $number
     * @param int $decimals
     * @return float
     */
    public static function floor($number, $decimals = 0)
    {
        $x = str_pad('1', 1 + $decimals, '0', STR_PAD_RIGHT);
        $result = floor($number * $x) / $x;

        // Fix decimal places precision
        return round($result, $decimals);
    }

    /**
     * Calculates percentage from two numbers
     * @param float $original
     * @param float $new
     * @param bool $factor If enabled, `75%` will result in `0.75`.
     * @return float
     */
    public static function calculatePercentage($original, $new, $factor = true)
    {
        $result = ($original - $new) / $original;
        if(!$factor) {
            $result *= 100;
        }
        return $result;
    }

    /**
     * Returns percentage from number
     * @param float $number
     * @param float $percents
     * @return float
     */
    public static function getPercentage($number, $percents)
    {
        return $number / 100 * $percents;
    }

    /**
     * Increase number by percents
     * @param float $number
     * @param float $percents
     * @return float
     */
    public static function increaseByPercentage($number, $percents)
    {
        return $number + static::getPercentage($number, $percents);
    }

    /**
     * Increase number by percents
     * @param float $number
     * @param float $percents
     * @return float
     */
    public static function decreaseByPercentage($number, $percents)
    {
        return $number - static::getPercentage($number, $percents);
    }

    /**
     * Calculate original figure when a percentage increase has been added
     * @param float $number
     * @param float $percents
     * @return float
     */
    public static function removeIncreasedPercentage($number, $percents)
    {
        $x = 1 + ($percents / 100);
        return $number / $x;
    }
}