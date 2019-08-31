<?php


namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class MasterController extends Controller
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
                        'roles' => ['master'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->redirect(['bid/index']);
    }

}