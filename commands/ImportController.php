<?php


namespace app\commands;

use app\services\xml\ReadService;
use yii\console\Controller;


class ImportController extends Controller
{
    const BASE_PATH = '@webroot';
    const UPLOAD_FOLDER = 'xml-upload';

    private $uploadPath;

    public function __construct($id, $module, $config = [])
    {
        $this->uploadPath = \Yii::getAlias(self::BASE_PATH) . '/' . self::UPLOAD_FOLDER;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $files = $this->getFiles();

        foreach ($files as $file) {
            $this->import($file);
        }

        $this->removeFiles();
    }

    private function getFiles()
    {
        $path = glob($this->uploadPath . '/*.xml');
        $fileNames = array_map(function ($item) {return pathinfo($item, PATHINFO_BASENAME);}, $path);

        return $fileNames;
    }

    private function removeFiles()
    {
        array_map('unlink', glob($this->uploadPath . '/*.xml'));
    }

    private function import($fileName)
    {
        $service = new ReadService($fileName, self::UPLOAD_FOLDER);
        $service->setBids();
    }

}