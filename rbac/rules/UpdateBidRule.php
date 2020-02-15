<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidStatus;
use app\models\Workshop;
use yii\rbac\Rule;

class UpdateBidRule extends Rule
{
    public $name = 'isUpdatedBid';

    public function execute($user, $item, $params)
    {
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        if ($bid->status_id !== BidStatus::getId(BidStatus::STATUS_FILLED)) {
            return false;
        }

        $workshop = Workshop::find()->joinWith('masters', false)->where(['master.user_id' => $user])->one();

        if (is_null($workshop)) {
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