<?php


namespace app\controllers;


use app\models\BidImage;
use app\models\Manufacturer;
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

    public function actionActExcel($filename, $directory = null)
    {
        /* @todo access restrict */
        $directory = is_null($directory) ? Manufacturer::getActTemplateDirectory() : $directory;
        return \Yii::$app->response->sendFile($directory . $filename, $filename);
    }

    public function actionBidImage($id)
    {
        /* @var $bidImage \app\models\BidImage */
        $bidImage = BidImage::findOne($id);

        return \Yii::$app->response->sendFile($bidImage->getPath(), $bidImage->file_name);
    }

}