<?php


namespace app\controllers;


use app\models\Agency;
use app\models\BidImage;
use app\models\Manufacturer;
use app\models\TemplateModel;
use yii\filters\AccessControl;
use yii\web\Controller;

class DownloadController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionDefault($filename, $path)
    {
        return \Yii::$app->response->sendFile($path, $filename);
    }

    public function actionAgencyTemplate($templateId)
    {
        /* @todo access restrict */
        $template = TemplateModel::findOne($templateId);
        return \Yii::$app->response->sendFile($template->getTemplatePath());
    }

    public function actionBidImage($id)
    {
        /* @var $bidImage \app\models\BidImage */
        $bidImage = BidImage::findOne($id);

        return \Yii::$app->response->sendFile($bidImage->getPath(), $bidImage->file_name);
    }

}