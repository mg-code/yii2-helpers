<?php
namespace mgcode\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    const TRIM_BEGINNING = 1;
    const TRIM_END = 2;
    const TRIM_BOTH = 3;

    /**
     * Sets array keys from column
     * @param array $array
     * @param string|\Closure $column
     * @return array
     */
    public static function setKeyFromColumn(array $array, $column)
    {
        return static::map($array, $column, function ($object) {
            return $object;
        });
    }

    /**
     * Trims array by percents
     * @param array $array
     * @param float $trimmedPercent
     * @param int $position
     * @return array
     */
    public static function trimByPercents(array $array, $trimmedPercent = 0.1, $position = self::TRIM_BOTH)
    {
        $g = $trimmedPercent * count($array);
        $g = floor($g);

        // Trim values if we have to trim
        if ($g) {
            $offset = in_array($position, [static::TRIM_BEGINNING, static::TRIM_BOTH]) ? $g : 0;
            $array = array_slice($array, $offset, (count($array) - $g * 2));
        }

        return $array;
    }
}