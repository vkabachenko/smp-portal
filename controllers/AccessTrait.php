<?php


namespace app\controllers;


use yii\web\MethodNotAllowedHttpException;

trait AccessTrait
{

    public function checkAccess($permission, $parameters = [])
    {
        if (!\Yii::$app->user->can($permission, $parameters)) {
            \Yii::error($permission);
            throw new MethodNotAllowedHttpException();
        }
    }

}