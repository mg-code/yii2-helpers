<?php
namespace mgcode\helpers;

use Yii;

/**
 * Class ValidateExceptTrait
 * @package mgcode\helpers
 */
trait ValidateExceptTrait
{
    /**
     ** Performs the data validation with ability to specify which attributes should not be validated.
     *
     * @param array $attributeNames list of attribute names that should not be validated.
     * @param boolean $clearErrors whether to call [[clearErrors()]] before performing validation
     * @return boolean whether the validation is successful without any error.
     */
    public function validateExcept($attributeNames = [], $clearErrors = true)
    {
        $attributes = ArrayHelper::removeValues($this->activeAttributes(), $attributeNames);
        return $this->validate($attributes, $clearErrors);
    }
}