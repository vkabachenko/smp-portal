<?php

namespace app\controllers;

use app\helpers\CrmHelper;
use app\models\Client;
use app\models\Workshop;
use yii\rest\Controller;

class CrmController extends Controller
{
    public function actionGetClient()
    {
        $token = \Yii::$app->request->get('token');
        $workshop = $this->getWorkshop($token);
        $phone = \Yii::$app->request->get('phone');
        $phone = CrmHelper::purifyPhone($phone);

        return Client::getClientByPhone($phone, $workshop);
    }

    protected function verbs()
    {
        return [
            'get-client' => ['GET'],
        ];
    }


    /**
     * @param $token
     * @return Workshop
     */
    private function getWorkshop($token)
    {
        /* @var $workshop \app\models\Workshop */
        $workshop = Workshop::find()->where(['token' => $token])->one();

        if (is_null($workshop)) {
            throw new \DomainException('Workshop not found');
        }

        return $workshop;
    }
}