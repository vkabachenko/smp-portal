<?php

namespace app\controllers;

use app\models\form\SignupWorkshopForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\form\SignupAgencyForm;
use app\services\mail\InviteAgency;
use app\models\form\ResetPasswordRequestForm;
use app\services\mail\ResetPassword;
use app\services\mail\InviteWorkshop;
use app\models\form\CreatePasswordForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        try {
            return $this->redirect([\Yii::$app->user->identity->role .'/index']);
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new NotFoundHttpException('User cabinet not found');
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignupAgency()
    {
        $model = new SignupAgencyForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            $mailService = new InviteAgency($model->manager, $model->is_independent);
            if ($mailService->send()) {
                \Yii::$app->session->setFlash('success', 'Направлено письмо администратору для одобрения вашего запроса. Дождитесь письма с одобрением от администратора');
            } else {
                \Yii::$app->session->setFlash('error', 'Не удалось отправить письмо на e-mail администратора. Попробуйте зарегистрироваться позднее');
            }

            return $this->goHome();
        }
        return $this->render('signup-agency', [
            'model' => $model,
        ]);
    }

    public function actionSignupWorkshop()
    {
        $model = new SignupWorkshopForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            $mailService = new InviteWorkshop($model->master);
            if ($mailService->send()) {
                \Yii::$app->session->setFlash('success', 'Направлено письмо администратору для одобрения вашего запроса. Дождитесь письма с одобрением от администратора');
            } else {
                \Yii::$app->session->setFlash('error', 'Не удалось отправить письмо на e-mail администратора. Попробуйте зарегистрироваться позднее');
            }
            return $this->goHome();
        }
        return $this->render('signup-workshop', [
            'model' => $model,
        ]);
    }

    public function actionResetPasswordRequest()
    {
        $model = new ResetPasswordRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->getUser();
            $mailService = new ResetPassword($user);

            if ($mailService->send()) {
                \Yii::$app->session->setFlash('success', 'На ваш e-mail отправлена ссылка на восстановление пароля');
            } else {
                \Yii::$app->session->setFlash('error', 'Не удалось отправить письмо на ваш e-mail. Обратитесь к администратору сайта или попробуйте позднее');
            }

            return $this->goHome();
        }
        return $this->render('reset-password-request', [
            'model' => $model,
        ]);
    }

    public function actionCreatePassword($token)
    {
        $user = User::findByPasswordResetToken($token);
        if (is_null($user)) {
            throw new \DomainException('User not found');
        }
        $model = new CreatePasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            $user->setPassword($model->password);
            $user->password_reset_token = null;
            if (!$user->save()) {
                \Yii::error($user->getErrors());
                \Yii::$app->session->setFlash('error', 'Не удалось сохранить измененный пароль. Обратитесь к администратору сайта или попробуйте позднее');
            }
            \Yii::$app->session->setFlash('success', 'Пароль успешно изменен');
            return $this->goHome();
        }

        return $this->render('create-password', [
            'model' => $model,
        ]);
    }

}
