<?php


namespace app\controllers;

use app\models\form\UploadXmlForm;
use app\services\xml\BaseService;
use app\services\xml\ReadService;
use app\services\xml\WriteService;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\Response;

class ExchangeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'import') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


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

    public function actionImport()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (!\Yii::$app->request->isPost) {
            \Yii::error('incorrect request');
            return ['status' => 'incorrect request type'];
        }

        $content = \Yii::$app->request->getRawBody();
        \Yii::info($content);

        $path =  \Yii::getAlias('@webroot') . '/xml/test.xml';
        file_put_contents($path, $content);

        $service = new ReadService('test.xml');
        $service->setBids();

        return ['status' => 'OK'];
    }

}