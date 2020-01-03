<?php


namespace app\assets;

use yii\web\AssetBundle;

class SweetalertAsset extends AssetBundle
{
    public $sourcePath = '@npm/sweetalert';
    public $js = [
        'dist/sweetalert.min.js'
    ];

}