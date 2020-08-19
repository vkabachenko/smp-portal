<?php

namespace app\controllers;

use app\helpers\CrmHelper;
use app\models\Client;
use yii\rest\Controller;

class CrmController extends Controller
{
    public function actionGetClient()
    {
        $phone = \Yii::$app->request->get('phone');
        $phone = CrmHelper::purifyPhone($phone);

        return Client::getClientByPhone($phone);
    }

    protected function verbs()
    {
        return [
            'get-client' => ['GET'],
        ];
    }
}