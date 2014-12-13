<?php
namespace mgcode\helpers;

class MathHelper
{
    /**
     * Counts an average value from a list of values
     * @param array $values
     * @return float
     */
    public static function average(array $values)
    {
        return array_sum($values) / count($values);
    }

    /**
     * Calculates a trimmed mean, removes percent of values from beginning and end
     * @param array $values
     * @param float $trimmedMeanPercent
     * @return float
     */
    public static function calculateTrimmedMean($values, $trimmedMeanPercent = 0.1)
    {
        $g = $trimmedMeanPercent * count($values);
        if ($g >= 1) {
            sort($values, SORT_NUMERIC);
            $values = ArrayHelper::trimByPercents($values, $trimmedMeanPercent);
        }
        return static::average($values);
    }

    /**
     * Calculates a median from a list of values
     * @param $values
     * @return float
     */
    public static function calculateMedian($values)
    {
        // Filter only positive values
        $toCareAbout = array();
        foreach ($values as $val) {
            if ($val >= 0) {
                $toCareAbout[] = $val;
            }
        }
        $count = count($toCareAbout);
        if ($count == 0) {
            return 0;
        }

        // If we're down here it must mean $toCareAbout has at least 1 item in the array.
        $middleIndex = (int) floor($count / 2);
        sort($toCareAbout, SORT_NUMERIC);
        $median = $toCareAbout[$middleIndex];

        // Handle the even case by averaging the middle 2 items
        if ($count % 2 == 0) {
            $median = ($median + $toCareAbout[$middleIndex - 1]) / 2;
        }

        return $median;
    }
}