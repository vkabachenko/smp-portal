<?php

namespace app\controllers;

use app\helpers\CrmHelper;
use app\models\Bid;
use app\models\Client;
use app\models\ClientPhone;
use app\models\StatusHistory1c;
use app\models\Workshop;
use yii\db\Expression;
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
            'get-active-clients' => ['POST'],
            'get-imported-authors' => ['GET'],
            'get-stats-change-statuses' => ['GET'],
            'get-bids-created' => ['GET']
        ];
    }

    public function beforeAction($action)
    {
        $token = \Yii::$app->request->get('token');
        $token = $token ?: \Yii::$app->request->post('token');
        $this->workshop = $this->getWorkshop($token);

        return parent::beforeAction($action);
    }

    public function actionGetClient()
    {
        $phone = \Yii::$app->request->get('phone');
        $phone = CrmHelper::purifyPhone($phone);

        return Client::getClientByPhone($phone, $this->workshop);
    }

    public function actionGetActiveClients()
    {
        $phones = \Yii::$app->request->post('phones');
        $normalizedPhones = array_map(function($phone) {return CrmHelper::purifyPhone($phone);},
            $phones);

        $foundPhones = ClientPhone::find()
            ->select(['normalized_phone' => new Expression('substr(regex_replace("[^0-9]", "", client_phone.phone), -10)')])
            ->joinWith('client.workshop', false)
            ->where(['workshop.id' => $this->workshop->id])
            ->having(['normalized_phone' => $normalizedPhones])
            ->column();

        $flagPhones = array_map(function($item) use ($foundPhones) {return array_search($item, $foundPhones) === false ? false : true;}
        , $normalizedPhones);

        return array_combine($phones, $flagPhones);
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

    public function actionGetImportedAuthors()
    {
        $authors = StatusHistory1c::find()
            ->joinWith('bid', false)
            ->select('status_history_1c.author')
            ->distinct()
            ->where(['bid.workshop_id' => $this->workshop->id])
            ->orderBy('author')
            ->column();
        return $authors;
    }

    public function actionGetStatsChangeStatuses()
    {
        $dateBegin = date('Y-m-d H:i:s', \Yii::$app->request->get('DateBegin'));
        $dateEnd = date('Y-m-d H:i:s', \Yii::$app->request->get('DateEnd'));

        $statuses = (new \yii\db\Query())
            ->from('status_history_1c')
            ->select(['cnt' => 'COUNT(id)', 'day' => 'DATE(date)', 'author'])
            ->where(['between', 'date', $dateBegin, $dateEnd])
            ->groupBy(['day', 'author'])
            ->all();

        return $statuses;
    }

    public function actionGetBidsCreated()
    {
        $dateBegin = date('Y-m-d H:i:s', \Yii::$app->request->get('DateBegin'));
        $dateEnd = date('Y-m-d H:i:s', \Yii::$app->request->get('DateEnd'));

        $expresssion = new Expression('MIN(date)');

        $subQuery = (new \yii\db\Query())
            ->select(['datemin' => $expresssion, 'bid_id'])
            ->from('status_history_1c')
            ->having(['between', 'datemin', $dateBegin, $dateEnd])
            ->groupBy('bid_id');

        $bids = (new \yii\db\Query())
            ->select(['cnt' => 'COUNT(status_history_1c.bid_id)', 'day' => 'DATE(u.datemin)', 'status_history_1c.author'])
            ->from('status_history_1c')
            ->innerJoin(['u' => $subQuery], 'u.bid_id = status_history_1c.bid_id AND u.datemin = status_history_1c.date' )
            ->groupBy(['day', 'status_history_1c.author'])
            ->all();

        return $bids;
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