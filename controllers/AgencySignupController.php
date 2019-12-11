<?php


namespace app\controllers;


use app\models\Agency;
use app\models\form\SignupManagerForm;
use app\models\LoginForm;
use app\models\Manager;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;

class AgencySignupController extends Controller
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

        $agency = Agency::find()
            ->joinWith('managers', false)
            ->where(['manager.user_id' => $user->id])
            ->one();
        if (is_null($agency)) {
            throw new \DomainException('Agency not found');
        }

        if ($agency->load(\Yii::$app->request->post()) && $agency->save())   {
            $user->verification_token = null;
            $user->status = User::STATUS_ACTIVE;
            $user->save();
            \Yii::$app->user->login($user, 0);
            LoginForm::assignRole();
            return $this->goHome();
        }
        return $this->render('index', [
            'agency' => $agency
        ]);
    }

}