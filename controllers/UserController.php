<?php

namespace app\controllers;

use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;




class UserController extends Controller
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

    public function actionProfile($id, $returnUrl)
    {
        $user = User::findOne($id);
        return $this->render('profile', compact('user', 'returnUrl'));
    }

}
