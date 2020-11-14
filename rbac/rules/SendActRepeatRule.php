<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use yii\rbac\Rule;

class SendActRepeatRule extends Rule
{
    public $name = 'isSendActRepeat';

    public function execute($user, $item, $params)
    {
        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);

        if (!$bid->isWarranty()) {
            return false;
        }

        if (is_null($bid->getAgency())) {
            return false;
        }

        $user = User::findOne($user);
        if (!$user) {
            return false;
        }

        /* @var $master \app\models\Master */
        $master = $user->master;
        if ($master
            && $master->workshop_id == $bid->workshop_id
            && $master->getBidRole() == Bid::TREATMENT_TYPE_WARRANTY
            && $bid->status_id !== BidStatus::getId(BidStatus::STATUS_FILLED)
            && $bid->status_id !== BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)) {
            return true;
        } else {
            return false;
        }

    }

}