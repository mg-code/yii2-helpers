<?php

namespace mgcode\helpers;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ActiveQueryHelperTrait
 * @package mgcode\helpers
 */
trait ActiveQueryHelperTrait
{
    use ActiveQueryAliasTrait;

    /**
     * Ability to customize query using an anonymous function.
     * @param callable $callback a valid PHP callback that customizes the query. Accepts query as parameter.
     * @return $this the query object itself.
     */
    public function touch(callable $callback)
    {
        call_user_func($callback, $this);
        return $this;
    }
}