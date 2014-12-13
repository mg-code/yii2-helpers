<?php
namespace mgcode\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Sets array key from sub array column
     * Usually used for db results.
     * @param array $array
     * @param       $column
     * @return array
     */
    public static function setKeyFromColumn(array $array, $column)
    {
        return static::map($array, $column, function ($object) {
            return $object;
        });
    }

    /**
     * Trims array from start and end by percents
     * @param array $array
     * @param float $trimmedPercent
     * @return array
     */
    public static function trimSidesByPercents(array $array, $trimmedPercent = 0.1)
    {
        $g = $trimmedPercent * count($array);
        $g = floor($g);

        // Trim values if we have to trim
        if ($g) {
            $array = array_slice($array, $g, (count($array) - $g * 2));
        }

        return $array;
    }
}