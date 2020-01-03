<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $cssFiles = [
        'css/site.css',
    ];
    public $jsFiles = [
        'js/site.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        SweetalertAsset::class
    ];

    public function init()
    {
        $this->css = $this->getVersionedFiles($this->cssFiles);
        $this->js = $this->getVersionedFiles($this->jsFiles);

        parent::init();
    }

    private function getVersionedFiles($files)
    {
        $out = [];

        foreach ($files as $file) {
            $filePath = \Yii::getAlias($this->basePath . '/' . $file);
            $out[] = $file . (is_file($filePath) ? '?v='.filemtime($filePath) : '');
        }

        return $out;
    }
}
