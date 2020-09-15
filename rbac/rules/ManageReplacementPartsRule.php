<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\JobsCatalog;
use app\models\User;
use yii\rbac\Rule;

class ManageReplacementPartsRule extends Rule
{
    public $name = 'isManageReplacementParts';

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

        if ($master = $userModel->master) {
            return $master->getBidRole() === Bid::TREATMENT_TYPE_PRESALE
                && $master->workshop_id == $bid->workshop_id;
        }

        return false;

    }

}