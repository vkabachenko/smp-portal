<?php


namespace app\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class AdminController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        Url::remember();
        return $this->render('index');
    }
}