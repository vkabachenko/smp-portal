<?php


namespace app\rbac\rules;

use app\models\BidHistory;
use app\models\User;
use yii\rbac\Rule;

class UpdateBidStatusRule extends Rule
{
    public $name = 'isUpdateBidStatus';

    public function execute($user, $item, $params)
    {
        if (BidHistory::isBidDone($params['bidId'])) {
            return false;
        }

        $sentBidStatus = BidHistory::sentBidStatus($params['bidId']);

        if (is_null($sentBidStatus)) {
            return false;
        }

        $user = User::findOne($user);
        if (!$user) {
            return false;
        }

        if (
            ($user->master && $sentBidStatus == BidHistory::BID_SENT_AGENCY) ||
            ($user->manager && $sentBidStatus == BidHistory::BID_SENT_WORKSHOP)
        ) {
            return true;
        } else {
            return false;
        }
    }

}