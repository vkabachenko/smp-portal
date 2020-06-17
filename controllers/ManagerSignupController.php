<?php


namespace app\controllers;


use app\models\form\SignupManagerForm;
use app\models\Manager;
use yii\filters\AccessControl;
use yii\web\Controller;

class ManagerSignupController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($token)
    {
        $manager = Manager::find()->where(['invite_token' => $token])->one();

        if (is_null($manager)) {
            throw new \DomainException('Token not found');
        }

        $model = new SignupManagerForm($manager);
        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

}