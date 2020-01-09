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
        /* @var $bidHistory BidHistory */
        $bidHistory = BidHistory::find()
            ->where(['bid_id' => $params['bidId']])
            ->andWhere(['or', ['action' => BidHistory::BID_SENT_WORKSHOP], ['action' => BidHistory::BID_SENT_AGENCY]])
            ->orderBy('created_at DESC')
            ->one();

        if (!$bidHistory) {
            return false;
        }

        $user = User::findOne($user);
        if (!$user) {
            return false;
        }

        if (
            ($user->master && $bidHistory->action == BidHistory::BID_SENT_AGENCY) ||
            ($user->manager && $bidHistory->action == BidHistory::BID_SENT_WORKSHOP)
        ) {
            return true;
        } else {
            return false;
        }
    }

}