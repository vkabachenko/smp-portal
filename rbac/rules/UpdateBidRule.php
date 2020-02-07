<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidHistory;
use app\models\Master;
use app\models\Workshop;
use yii\rbac\Rule;

class UpdateBidRule extends Rule
{
    public $name = 'isUpdatedBid';

    public function execute($user, $item, $params)
    {
        if (BidHistory::isBidDone($params['bidId'])) {
            return false;
        }

        $isSent = BidHistory::find()
            ->where(['bid_id' => $params['bidId'], 'action' => BidHistory::BID_SENT_WORKSHOP])
            ->exists();

        if ($isSent) {
            return false;
        }

        $workshop = Workshop::find()->joinWith('masters', false)->where(['master.user_id' => $user])->one();

        if (is_null($workshop)) {
            return false;
        }

        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        if ($bid->workshop_id != $workshop->id) {
            return false;
        }

        $rules = $workshop->rules;

        if (!isset($rules['paidBid'])) {
            return true;
        }

        if ($rules['paidBid']) {
           return true;
        } else {
            if (!$bid->isPaid()) {
                return true;
            } else {
                return false;
            }
        }

    }

}