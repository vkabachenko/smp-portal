<?php


namespace app\controllers;

use app\services\xml\BaseService;
use app\services\xml\ReadService;
use app\services\xml\WriteService;
use yii\web\Controller;

class ExchangeController extends Controller
{
    public function actionRead($filename = 'test.xml')
    {
        /*$service = new ReadService($filename);
        $service->setBids();*/

        return 'OK';
    }

    public function actionWrite($filename = 'test.xml')
    {
        $service = new WriteService($filename);
        $service->createXmlfile();

        return $this->render('write', ['filename' => $filename]);
    }

    public function actionDownload($filename)
    {
        $service = new BaseService($filename);
        return \Yii::$app->response->sendFile($service->filename, $filename );
    }



}