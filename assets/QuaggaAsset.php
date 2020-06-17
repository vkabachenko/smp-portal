<?php


namespace app\assets;

use yii\web\AssetBundle;

class QuaggaAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/quagga';
    public $js = [
        'dist/quagga.min.js',
    ];
}