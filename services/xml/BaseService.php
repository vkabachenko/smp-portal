<?php


namespace app\services\xml;


class BaseService
{
    const BASE_PATH = '@webroot' . '/xml/';
    public $filename;

    public function __construct($filename)
    {
        $this->filename = \Yii::getAlias(self::BASE_PATH) . $filename;
    }

}