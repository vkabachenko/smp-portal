<?php


namespace app\controllers;


use app\models\Agency;
use app\models\form\SignupManagerForm;
use app\models\LoginForm;
use app\models\Manager;
use app\models\User;
use app\models\Workshop;
use yii\filters\AccessControl;
use yii\web\Controller;

class WorkshopSignupController extends Controller
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
        /* @var $user \app\models\User */
        $user = User::find()->where(['verification_token' => $token])->one();

        if (is_null($user)) {
            throw new \DomainException('Token not found');
        }

        $workshop = Workshop::find()
            ->joinWith('masters', false)
            ->where(['master.user_id' => $user->id])
            ->one();
        if (is_null($workshop)) {
            throw new \DomainException('Workshop not found');
        }

        $user->verification_token = null;
        $user->status = User::STATUS_ACTIVE;
        $user->save();
        \Yii::$app->user->login($user, 0);
        LoginForm::assignRole();

        \Yii::$app->session->setFlash('success', 'Мастерская успешно зарегистрирована');

        return $this->goHome();
    }

}