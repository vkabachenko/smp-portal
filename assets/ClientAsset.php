<?php


namespace app\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ClientAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/client.js'
    ];

    public $depends = [
        JqueryAsset::class
    ];

}