<?php
namespace mgcode\helpers;

use Yii;

class Html extends \yii\helpers\Html
{
    /**
     * Added possibility to disable label.
     * @inheritdoc
     */
    public static function checkbox($name, $checked = false, $options = [])
    {
        $options['checked'] = (bool) $checked;
        $value = array_key_exists('value', $options) ? $options['value'] : '1';
        if (isset($options['uncheck'])) {
            // add a hidden field so that if the checkbox is not selected, it still submits a value
            $hidden = static::hiddenInput($name, $options['uncheck']);
            unset($options['uncheck']);
        } else {
            $hidden = '';
        }

        // Checks if label is enabled, by default: yes.
        $enableLabel = isset($options['enableLabel']) ? (bool) $options['enableLabel'] : true;
        unset($options['enableLabel']);

        if (isset($options['label']) && $enableLabel) {
            $label = $options['label'];
            $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
            unset($options['label'], $options['labelOptions']);
            $content = static::label(static::input('checkbox', $name, $value, $options) . ' ' . $label, null, $labelOptions);
            return $hidden . $content;
        } else {
            return $hidden . static::input('checkbox', $name, $value, $options);
        }
    }
}