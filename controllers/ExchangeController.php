<?php


namespace app\controllers;

use app\models\Bid;
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
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionRead($filename = 'import_manual.xml')
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

    public function actionWrite($filename = 'export_manual.xml')
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
        if (!\Yii::$app->request->isPost) {
            throw new \Exception('Incorrect request type');
        }

        $responseArray = $this->importFile('ReadService', $this->setFileName('import'));

        $service = new WriteService($this->setFileName('import-response'), $responseArray);
        $service->createXmlfile();

        $xml = file_get_contents($service->filename);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = \Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        return $xml;
    }

    public function actionExport()
    {
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $responseArray = $this->importFile('ExportResponseService', $this->setFileName('export-response'));
            return $responseArray;
        }

        $service = new WriteService($this->setFileName('export'));
        $service->createXmlfile();

        $xml = file_get_contents($service->filename);
        Bid::setAllFlagexport(true);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = \Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        return $xml;
    }

    /**
     * @param $servicename string
     * @param $filename string
     * @throws \Exception
     * @fixme Use DI to apply service
     * @return array
     */
    private function importFile($servicename, $filename)
    {
        $serviceNamespace = 'app\services\xml';

        if (empty($_FILES)) {
            throw new \Exception('File not found');
        }
        reset($_FILES);
        $fileName = key($_FILES);

        $path =  \Yii::getAlias('@webroot') . '/xml/' . $filename;
        @unlink($path);

        $upload = UploadedFile::getInstanceByName($fileName);

        $upload->saveAs($path);

        $serviceFullName = $serviceNamespace . '\\' . $servicename;
        $service = new $serviceFullName($filename);
        $responseArray = $service->setBids();

        return $responseArray;
    }

    private function setFileName($template)
    {
        $datePart = date("dmYHis");

        return $template . $datePart . '.xml';
    }

}