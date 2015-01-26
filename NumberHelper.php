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
}