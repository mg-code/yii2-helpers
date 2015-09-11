<?php

namespace mgcode\helpers;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for helpers
 */
class HelpersAsset extends AssetBundle
{
    public $sourcePath = '@mgcode/helpers/assets';
    public $js = [
        'mgcode.helpers.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
