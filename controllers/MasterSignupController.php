<?php


namespace app\controllers;


use app\models\form\SignupMasterForm;
use app\models\MasterSignup;
use yii\filters\AccessControl;
use yii\web\Controller;

class MasterSignupController extends Controller
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
        /* @var $masterSignupModel \app\models\MasterSignup */
        $masterSignupModel = MasterSignup::find()->where(['token' => $token])->one();
        if (is_null($masterSignupModel)) {
            throw new \DomainException('Token not found');
        }
        if (time() - strtotime($masterSignupModel->created_at) > self::EXPIRED_TOKEN_PERIOD) {
            throw new \DomainException('Token is expired');
        }

        $model = new SignupMasterForm();
        if ($model->load(\Yii::$app->request->post()) && $model->signup($masterSignupModel)) {
            return $this->goHome();
        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }

}