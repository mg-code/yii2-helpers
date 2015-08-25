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
}