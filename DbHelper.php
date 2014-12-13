<?php

namespace mgcode\helpers;

class DbHelper extends \yii\base\Component
{
    /**
     * Quotes array values for use in a query.
     * Note that if a value is not a string, it will be returned without change.
     * @static
     * @param array $array
     * @param \yii\db\Connection|null $connection If null, will be used - \Yii::$app->db
     * @return array
     * @see http://www.php.net/manual/en/function.PDO-quote.php
     */
    public static function quoteValueArray($array, $connection = null)
    {
        if ($connection === null) {
            $connection = \Yii::$app->db;
        }

        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $connection->quoteValue($value);
        }

        return $result;
    }

    /**
     * Throws exception with model errors and attributes.
     * Usually used in CLI commands.
     * @param \yii\db\BaseActiveRecord $model
     * @throws \yii\db\Exception
     */
    public static function throwSaveException($model)
    {
        $className = get_class($model);
        $errors = print_r($model->errors, true);
        $attributes = print_r($model->attributes, true);

        $error = [
            "Failed to save: '{$className}'",
            "Errors: {$errors}",
            "Attributes: {$attributes}"
        ];

        throw new \yii\db\Exception(implode(" \r\n", $error));
    }

    /**
     * Implodes values for plain inserts
     * @param $values
     * @param \yii\db\Connection|null $connection
     * @return string
     */
    public static function implodeValues(array $values, $connection = null)
    {
        if ($connection === null) {
            $connection = \Yii::$app->db;
        }

        $implode = [];
        foreach ($values as $value) {
            if (is_object($value) && $value instanceof \yii\db\Expression) {
                $implode[] = $value->__toString();
            } else if ($value === null) {
                $implode[] = 'null';
            } else {
                $implode[] = $connection->quoteValue($value);
            }
        }

        return implode(', ', $implode);
    }

    /**
     * Implodes insert values and splits into chunks.
     * Usually it is used in CLI commands for big data inserts.
     *
     * E.g. if you will specify size of chunks - 4:
     * [
     *    // Data type - string
     *    '(1, 1, 1), (3, 2, 3), (2, 2, 1), (3, 2, 3)',
     *    '(2, 2, 2), (2, 3, 3), (3, 1, 1), (3, 2, 3)',
     *    '(1, 3, 1), (2, 1, 2), (2, 3, 1), (2, 1, 2)',
     *    '(2, 2, 2), (3, 2, 1), (2, 2, 3), (2, 1, 1)',
     * ]
     *
     * E.g. data usage:
     * foreach ($queries as $values) {
     *   $sql = 'INSERT DELAYED INTO `statistic_aggregate` (`reference_type`, `reference_id`, `date`, `value`) VALUES ' . $values;
     *   $sql .= ' ON DUPLICATE KEY UPDATE `value` = `value`, `updated` = NOW();';
     *   $db->createCommand($sql)->execute();
     * }
     *
     * @param array $data The collection of values to work on
     * @param int $size The size of each chunk
     * @param \yii\db\Connection|null $connection
     * @return array
     */
    public static function buildInsertQueries($data, $size = 10000, $connection = null)
    {
        if ($connection === null) {
            $connection = \Yii::$app->db;
        }

        // Split into chunks
        $chunks = array_chunk($data, $size);

        $result = [];
        foreach ($chunks as $chunk) {
            $inserts = [];
            foreach ($chunk as $values) {
                $inserts[] = '('.static::implodeValues($values, $connection).')';
            }

            $result[] = implode(', ', $inserts);
        }

        return $result;
    }
}