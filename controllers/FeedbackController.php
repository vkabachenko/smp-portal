<?php

namespace app\controllers;

use app\models\form\MultipleUploadForm;
use app\services\mail\Feedback;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

class FeedbackController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionSend()
    {
        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }

        $mailService = new Feedback(
            \Yii::$app->request->post('subject', ''),
            \Yii::$app->request->post('message', ''),
            \Yii::$app->request->post('files', []),
            \Yii::$app->user->identity
        );

        return $mailService->send();
    }

}