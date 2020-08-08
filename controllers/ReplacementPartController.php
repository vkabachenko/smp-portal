<?php


namespace app\controllers;


use app\models\ReplacementPart;
use yii\filters\AccessControl;
use yii\web\Controller;

class ReplacementPartController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('manageReplacementParts');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        $model = ReplacementPart::findOne($id);

        return $this->render('view', compact('model'));
    }

}