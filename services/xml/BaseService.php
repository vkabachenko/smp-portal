<?php


namespace app\services\xml;


class BaseService
{
    const BASE_PATH = '@webroot';
    public $filename;

    public function __construct($filename, $folder = 'xml')
    {
        $this->filename = \Yii::getAlias(self::BASE_PATH) . '/' . $folder . '/' . $filename;
    }

}