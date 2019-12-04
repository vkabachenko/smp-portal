<?php


namespace app\controllers;


use app\models\form\SignupManagerForm;
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
        /* @var $managerSignupModel \app\models\ManagerSignup */
        $managerSignupModel = ManagerSignup::find()->where(['token' => $token])->one();
        if (is_null($managerSignupModel)) {
            throw new \DomainException('Token not found');
        }
        if (time() - strtotime($managerSignupModel->created_at) > self::EXPIRED_TOKEN_PERIOD) {
            throw new \DomainException('Token is expired');
        }

        $model = new SignupManagerForm();
        if ($model->load(\Yii::$app->request->post()) && $model->signup($managerSignupModel)) {
            return $this->goHome();
        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }

}