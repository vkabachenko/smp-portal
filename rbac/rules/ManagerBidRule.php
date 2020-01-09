<?php


namespace app\rbac\rules;


use app\models\Agency;
use app\models\Bid;
use app\models\BidHistory;
use app\models\Workshop;
use yii\rbac\Rule;

class ManagerBidRule extends Rule
{
    public $name = 'isManagerdBid';

    public function execute($user, $item, $params)
    {
        $isSent = BidHistory::find()
            ->where(['bid_id' => $params['bidId'], 'action' => BidHistory::BID_SENT_WORKSHOP])
            ->exists();

        if (!$isSent) {
            return false;
        }

        $agency = Agency::find()
            ->with('workshops')
            ->joinWith('managers', false)
            ->where(['manager.user_id' => $user])
            ->one();

        if (is_null($agency)) {
            return false;
        }

        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $workshops = array_map(function(Workshop $workshop) { return $workshop->id; }, $agency->workshops);

        if (in_array($bid->workshop_id, $workshops)
            && $bid->manufacturer_id == $agency->manufacturer_id
            && $bid->isWarranty()) {
            return true;
        } else {
            return false;
        }
    }

}