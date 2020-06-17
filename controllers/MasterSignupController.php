<?php


namespace app\controllers;


use app\models\form\SignupMasterForm;
use app\models\Master;
use yii\filters\AccessControl;
use yii\web\Controller;

class MasterSignupController extends Controller
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
        $master = Master::find()->where(['invite_token' => $token])->one();

        if (is_null($master)) {
            throw new \DomainException('Token not found');
        }

        $model = new SignupMasterForm($master);
        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

}