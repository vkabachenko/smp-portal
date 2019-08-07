<?php


namespace app\controllers;

use app\models\form\UploadXmlForm;
use app\services\xml\BaseService;
use app\services\xml\ReadService;
use app\services\xml\WriteService;
use yii\web\Controller;
use yii\web\UploadedFile;

class ExchangeController extends Controller
{
    public function actionRead($filename = 'test.xml')
    {
        $uploadForm = new UploadXmlForm();
        if (\Yii::$app->request->isPost) {
            $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
            $uploadForm->upload($filename);
            $service = new ReadService($filename);
            $service->setBids();
            return $this->redirect(['admin/index']);
        }

        return $this->render('read', ['uploadForm' => $uploadForm]);
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