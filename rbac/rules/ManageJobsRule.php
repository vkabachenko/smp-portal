<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidHistory;
use app\models\Manager;

use app\models\User;
use DeepCopy\f001\B;
use yii\rbac\Rule;

class ManageJobsRule extends Rule
{
    public $name = 'isManageJobs';

    public function execute($user, $item, $params)
    {
        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $agency = $bid->getAgency();
        if (is_null($agency)) {
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

        if ($manager = $userModel->manager) {
            return $manager->agency_id == $agency->id && $sentBidStatus == BidHistory::BID_SENT_WORKSHOP;
        }

        if ($master = $userModel->master) {
            return $master->workshop_id == $bid->workshop_id && $sentBidStatus == BidHistory::BID_SENT_AGENCY;
        }

        return false;

    }

}