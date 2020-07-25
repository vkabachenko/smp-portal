<?php


namespace app\controllers;


use app\models\PageHelper;
use yii\filters\AccessControl;
use yii\web\Controller;

class PageHelperController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionSave()
    {
        $model = new PageHelper();
        $model->load(\Yii::$app->request->post());

        /* @var $existingModel PageHelper */
        $existingModel = PageHelper::find()->where(['controller' => $model->controller, 'action' => $model->action])->one();

        if ($existingModel) {
            $existingModel->help_text = $model->help_text;
            return $existingModel->save();
        } else {
            return $model->save();
        }
    }

}