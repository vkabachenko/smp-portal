<?php


namespace app\services\xml;


class BaseService
{
    const BASE_PATH = '@webroot';
    public $filename;

    /* @var \app\models\Workshop */
    public $workshop;

    public function __construct($filename, $workshop, $folder = 'xml')
    {
        $this->filename = \Yii::getAlias(self::BASE_PATH) . '/' . $folder . '/' . $filename;
        $this->workshop = $workshop;
    }

}