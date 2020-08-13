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
            try {
                \Yii::$app->mailer
                    ->compose(
                        [
                            'html' => 'invite-agency-success/html'
                        ],
                        [
                            'manager' => $user->manager,
                        ]
                    )
                    ->setFrom(\Yii::$app->params['adminEmail'])
                    ->setTo(
                        $user->email
                    )
                    ->setSubject('Успешная регистрация представительства ' . $user->manager->agency->name)
                    ->send();
                \Yii::$app->session->setFlash('success', 'Представительство успешно зарегистрировано');
            } catch (\Exception $exc) {
                \Yii::error($exc->getMessage());
                \Yii::$app->session->setFlash('error', 'Ошибка при отправке письма менеджеру');
            }
        }
        return $this->render('index', [
            'agency' => $agency
        ]);
    }

    public function actionIndexAdmin($token)
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
        /* @var $agency \app\models\Agency */
        $agency->is_independent = true;

        if ($agency->load(\Yii::$app->request->post()) && $agency->save())   {
            $user->verification_token = null;
            $user->status = User::STATUS_ACTIVE;
            $user->save();
            \Yii::$app->session->setFlash('success', 'Представительство зарегистрировано');
            return $this->goHome();
        }
        return $this->render('index', [
            'agency' => $agency
        ]);
    }

}