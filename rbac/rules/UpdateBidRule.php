<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
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

        $userModel = User::findOne($user);

        if ($master = $userModel->master) {
            /* @var $master \app\models\Master */
            if ($master->workshop->canManagePaidBid()) {
                return $master->workshop_id == $bid->workshop_id;
            } else {
                if ($master->workshop_id != $bid->workshop_id) {
                    return false;
                }
                if ($bid->status_id !== BidStatus::getId(BidStatus::STATUS_FILLED)) {
                    $agency = $bid->getAgency();
                    if ($agency && !$agency->is_independent) {
                        return false;
                    }
                }

                if ($bid->status_id === BidStatus::getId(BidStatus::STATUS_DONE)) {
                    return false;
                }

                return $bid->isWarranty();
            }

        } else {
            return false;
        }

    }

}