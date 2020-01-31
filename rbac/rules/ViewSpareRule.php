<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidHistory;
use app\models\User;
use yii\rbac\Rule;

class ViewSpareRule extends Rule
{
    public $name = 'isViewSpare';

    public function execute($user, $item, $params)
    {
        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $userModel = User::findOne($user);
        if (is_null($userModel)) {
            return false;
        }

        if ($userModel->role === 'admin') {
            return true;
        }

        $sentBidStatus = BidHistory::sentBidStatus($params['bidId']);

        /* @var $master \app\models\Master */
        if ($master = $userModel->master) {
            return $master->workshop_id == $bid->workshop_id
                && (is_null($sentBidStatus) || $sentBidStatus == BidHistory::BID_SENT_AGENCY);
        }

        $agency = $bid->getAgency();
        if (is_null($agency)) {
            return false;
        }

        /* @var $manager \app\models\Manager */
        if ($manager = $userModel->manager) {
            return $manager->agency_id == $agency->id && $sentBidStatus == BidHistory::BID_SENT_WORKSHOP;

        }

        return false;

    }

}