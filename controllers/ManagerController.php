<?php


namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class ManagerController extends Controller
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
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->redirect(['bid/index', 'title' => 'Личный кабинет менеджера']);
    }
}