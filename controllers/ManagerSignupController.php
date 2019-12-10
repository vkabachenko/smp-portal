<?php


namespace app\controllers;


use app\models\form\SignupManagerForm;
use app\models\Manager;
use app\models\ManagerSignup;
use yii\filters\AccessControl;
use yii\web\Controller;

class ManagerSignupController extends Controller
{
    const EXPIRED_TOKEN_PERIOD = 30 * 24 * 60 * 60;

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
        if ($model->load(\Yii::$app->request->post()) && $model->signup($manager)) {
            return $this->goHome();
        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }

}