<?php
namespace mgcode\helpers;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ActiveQueryAliasTrait
 * @package mgcode\helpers
 */
trait ActiveQueryAliasTrait
{
    /**
     * Returns table alias
     * @return string
     * @throws InvalidConfigException
     */
    public function getTableAlias()
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = $this;

        $from = $query->from ? $query->from : [];
        if (empty($from)) {
            /* @var $modelClass \yii\db\ActiveRecord */
            $modelClass = $query->modelClass;
            $tableName = $modelClass::tableName();
            $from = [$tableName];
        }

        $alias = null;
        foreach ((array) $from as $a => $table) {
            if (is_string($a)) {
                $alias = $a;
            } elseif (is_string($table)) {
                if (preg_match('/^(.*?)\s+({{\w+}}|\w+)$/', $table, $matches)) {
                    $alias = $matches[2];
                } else {
                    $alias = $table;
                }
            }
            break;
        }

        if($alias === null) {
            throw new InvalidConfigException('Could not find table alias, make sure you properly configured table name.');
        }

        return $alias;
    }
}