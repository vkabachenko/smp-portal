<?php

namespace app\controllers;

use app\helpers\CrmHelper;
use app\models\Bid;
use app\models\Client;
use app\models\ClientPhone;
use app\models\Workshop;
use yii\rest\Controller;

class CrmController extends Controller
{
    protected function verbs()
    {
        return [
            'get-client' => ['GET'],
            'get-client-by-bid-number' => ['GET'],
        ];
    }

    public function actionGetClient()
    {
        $token = \Yii::$app->request->get('token');
        $workshop = $this->getWorkshop($token);
        $phone = \Yii::$app->request->get('phone');
        $phone = CrmHelper::purifyPhone($phone);

        return Client::getClientByPhone($phone, $workshop);
    }

    public function actionGetClientByBidNumber()
    {
        $token = \Yii::$app->request->get('token');
        $workshop = $this->getWorkshop($token);
        $bid1CNumber = \Yii::$app->request->get('bid_1C_number');

        $bid = Bid::find()->where(['workshop_id' => $workshop->id, 'bid_1C_number' => $bid1CNumber])->one();

        return $bid ? $bid->client : null;
    }

    public function actionAddPhoneToClient()
    {
        $token = \Yii::$app->request->get('token');
        $workshop = $this->getWorkshop($token);
        $clientId = \Yii::$app->request->get('clientId');
        $phone = \Yii::$app->request->get('phone');

        $clientPhone = new ClientPhone(['client_id' => $clientId, 'phone' => $phone]);

        if (!$clientPhone->save()) {
            throw new \DomainException('fail to save client phone');
        }

        return Client::findOne($clientId);
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