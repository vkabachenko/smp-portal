<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\Spare;
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

        /* @var $master \app\models\Master */
        if ($master = $userModel->master) {
            if ($master->getBidRole() === Bid::TREATMENT_TYPE_WARRANTY
                && $master->workshop_id == $bid->workshop_id
                && ($bid->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)
                    || $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP))) {
                return true;
            } else {
                return false;
            }
        }

        $agency = $bid->getAgency();
        if (is_null($agency)) {
            return false;
        }

        /* @var $manager \app\models\Manager */
        if ($manager = $userModel->manager) {
           if ($manager->agency_id == $agency->id
                && $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_AGENCY)) {
               return true;
           } else {
               return false;
           }

        }

        return false;

    }
}