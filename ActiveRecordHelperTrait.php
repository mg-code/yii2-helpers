<?php
namespace mgcode\helpers;

use Yii;
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
}