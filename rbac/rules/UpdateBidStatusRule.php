<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use yii\rbac\Rule;

class UpdateBidStatusRule extends Rule
{
    public $name = 'isUpdateBidStatus';

    public function execute($user, $item, $params)
    {
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $user = User::findOne($user);
        if (!$user) {
            return false;
        }

        if (
            ($user->master && $bid->status_id == BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)) ||
            ($user->manager && $bid->status_id == BidStatus::getId(BidStatus::STATUS_READ_AGENCY))
        ) {
            return true;
        } else {
            return false;
        }
    }

}