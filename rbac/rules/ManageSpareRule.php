<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\Spare;
use app\models\User;
use yii\rbac\Rule;

class ManageSpareRule extends Rule
{
    public $name = 'isManageSpare';

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
            if ($master->workshop_id == $bid->workshop_id
                && ($bid->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)
                    || $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP))) {
                if (isset($params['id'])) {
                    if ($bid->treatment_type == Bid::TREATMENT_TYPE_PRESALE) {
                        return $master->workshop->canManagePaidBid();
                    } else {
                        $spare = Spare::findOne($params['id']);
                        if ($spare->is_paid) {
                            return $master->workshop->canManagePaidBid();
                        } else {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        return false;

    }

}