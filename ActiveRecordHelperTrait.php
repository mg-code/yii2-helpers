<?php
namespace mgcode\helpers;

use Yii;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordHelperTrait
 * @package mgcode\helpers
 */
trait ActiveRecordHelperTrait
{
    /**
     * Saves ActiveRecord model or throw exception.
     *
     * @param boolean $runValidation whether to perform validation (calling [[validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array $attributeNames list of attribute names that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the saving succeeded (i.e. no validation errors occurred).
     * @throws \yii\db\Exception
     */
    public function saveOrFail($runValidation = true, $attributeNames = null)
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this;
        if($owner->save($runValidation, $attributeNames)) {
            return true;
        }

        DbHelper::throwSaveException($owner);
    }

    /**
     * Returns a value indicating whether the any of named attributes has been changed.
     * @param string $name the name of the attribute.
     * @param boolean $identical whether the comparison of new and old value is made for
     * identical values using `===`, defaults to `true`. Otherwise `==` is used for comparison.
     * @return boolean whether any of attributes has been changed
     */
    public function isAnyAttributeChanged($attributes, $identical = true)
    {
        /** @var BaseActiveRecord $this */
        if(!is_array($attributes)) {
            throw new InvalidParamException('type of `attributes` must be array.');
        }

        foreach($attributes as $attribute) {
            if($this->isAttributeChanged($attribute, $identical)) {
                return true;
            }
        }
        return false;
    }
}