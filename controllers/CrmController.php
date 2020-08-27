<?php

namespace app\controllers;

use app\helpers\CrmHelper;
use app\models\Bid;
use app\models\Client;
use app\models\ClientPhone;
use app\models\Workshop;
use yii\helpers\Json;
use yii\rest\Controller;

class CrmController extends Controller
{
    /* @var Workshop */
    public $workshop;

    protected function verbs()
    {
        return [
            'get-client' => ['GET'],
            'get-client-by-bid-number' => ['GET'],
        ];
    }

    public function beforeAction($action)
    {
        $token = \Yii::$app->request->get('token');
        $this->workshop = $this->getWorkshop($token);

        return parent::beforeAction($action);
    }

    public function actionGetClient()
    {
        $phone = \Yii::$app->request->get('phone');
        $phone = CrmHelper::purifyPhone($phone);

        return Client::getClientByPhone($phone, $this->workshop);
    }

    public function actionGetClientByBidNumber()
    {
        $bid1CNumber = \Yii::$app->request->get('bid_1C_number');

        $bid = Bid::find()->where(['workshop_id' => $this->workshop->id, 'bid_1C_number' => $bid1CNumber])->one();

        return $bid ? $bid->client : null;
    }

    public function actionAddPhoneToClient()
    {
        $clientId = \Yii::$app->request->get('clientId');
        $phone = \Yii::$app->request->get('phone');

        $clientPhone = new ClientPhone(['client_id' => $clientId, 'phone' => $phone]);

        if (!$clientPhone->save()) {
            throw new \DomainException(Json::encode($clientPhone->getErrors()));
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