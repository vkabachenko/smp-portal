<?php

namespace app\controllers;


use app\models\Agency;
use app\models\form\UploadExcelTemplateForm;
use yii\filters\AccessControl;
use yii\web\Controller;

class AgencyTemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('updateAgency');
                        }
                    ],
                ],
            ]
        ];
    }

    public function actionIndex($agencyId)
    {
        $agency = Agency::findOne($agencyId);
        return $this->render('index', compact('agency'));
    }

    public function actionUpload($agencyId, $type)
    {
        $agency = Agency::findOne($agencyId);
        $uploadForm = new UploadExcelTemplateForm();

        if (\Yii::$app->request->isPost && $agency->saveWithUpload($type, $uploadForm)) {
            return $this->redirect(['index', 'agencyId' => $agencyId]);
        }
        return $this->render('upload', compact('agency', 'uploadForm'));
    }

}