<?php
namespace mgcode\helpers;

use yii\base\InvalidParamException;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    const TRIM_BEGINNING = 1;
    const TRIM_END = 2;
    const TRIM_BOTH = 3;

    /**
     * Returns array items in random order
     * @param array $array
     * @param integer $limit
     * @param bool $keepKeys whether to maintain the array keys. If false, the resulting array
     * will be re-indexed with integers.
     * @return array
     */
    public static function rand($array, $limit, $keepKeys = true)
    {

        $count = count($array);
        if ($count == 0) {
            return [];
        } else if ($count < $limit) {
            $limit = $count;
        }

        // Get random keys
        $rand = array_rand($array, $limit);
        if (!is_array($rand)) {
            $rand = [$rand];
        }

        $result = [];
        foreach ($rand as $i => $key) {
            $newKey = $keepKeys ? $key : $i;
            $result[$newKey] = $array[$key];
        }

        return $result;
    }

    /**
     * Performs circular shift
     * @param array $array
     * @param int $steps
     * @param bool $shiftLeft
     * @return array
     */
    public static function circularShift(array $array, $steps, $shiftLeft = false)
    {
        if (!is_int($steps)) {
            throw new InvalidParamException('Steps has to be an (int)');
        }
        $count = count($array);
        if ($count == 0 || $steps == 0) {
            return $array;
        }

        if ($shiftLeft) {
            $steps *= -1;
        }

        $steps = $steps % $count;
        $steps *= -1;

        return array_merge(
            array_slice($array, $steps),
            array_slice($array, 0, $steps)
        );
    }

    /**
     * Returns the values of a specified value column in an array and groups by specified group column.
     * @param array $array
     * @param string|\Closure $groupColumn
     * @param string|\Closure $valueColumn
     * @param boolean $keepKeys whether to maintain the array keys. If false, the resulting array
     * will be re-indexed with integers.
     * @return array
     */
    public static function groupColumn(array $array, $groupColumn, $valueColumn, $keepKeys = true)
    {
        $result = [];
        foreach ($array as $k => $element) {
            $group = static::getValue($element, $groupColumn);
            $value = static::getValue($element, $valueColumn);

            if ($keepKeys) {
                $result[$group][$k] = $value;
            } else {
                $result[$group][] = $value;
            }
        }

        return $result;
    }

    /**
     * Removes array values
     * @param array $array
     * @param array|mixed $elements Can be array
     * @return array
     */
    public static function removeValues(array $array, $elements)
    {
        if (!is_array($elements)) {
            $elements = [$elements];
        }
        return array_diff($array, $elements);
    }

    /**
     * Renames array column name
     * @param array $array
     * @param string $oldName
     * @param string $newName
     * @return array
     */
    public static function renameColumn(array $array, $oldName, $newName)
    {
        $result = [];
        foreach ($array as $key => $val) {
            $val[$newName] = $val[$oldName];
            unset($val[$oldName]);
            $result[$key] = $val;
        }
        return $result;
    }

    /**
     * Returns sum of the values of a specified column in an array.
     * The input array should be multidimensional or an array of objects.
     * @param array $array
     * @param string|\Closure $name
     * @return number
     */
    public static function getColumnSum(array $array, $name)
    {
        $values = static::getColumn($array, $name);
        return array_sum($values);
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
            $length = null;
            if ($position == static::TRIM_END) {
                $length = count($array) - $g;
            } else if ($position == static::TRIM_BOTH) {
                $length = count($array) - $g * 2;
            }
            $array = array_slice($array, $offset, $length);
        }

        return $array;
    }

    /**
     * Sorts arrays in specific order by column values
     * @param array $objects
     * @param string|\Closure $column key name of the array element, or property name of the object,
     * @param array $keys
     */
    public static function sortByColumnSpecific(array &$objects, $column, array $keys)
    {
        // We flip the array and use isset instead of in_array. Isset performs much faster.
        $keys = array_flip($keys);

        // Match array column to keys and organize objects into sub-arrays.
        // This is needed, because otherwise we need to iterate all objects for all keys.
        $organize = [];
        foreach ($objects as $k => $object) {
            $val = static::getValue($object, $column);
            if (isset($keys[$val])) {
                $index = $keys[$val];
                $organize[$index][] = $object;
                unset($objects[$k]);
            }
        }

        if ($organize) {
            // Sort organized array
            ksort($organize, SORT_NUMERIC);

            // Merge all sub arrays
            $result = call_user_func_array('array_merge', $organize);

            // Override the variable and merge with unsorted values
            $objects = array_merge($result, $objects);
        }
    }
}