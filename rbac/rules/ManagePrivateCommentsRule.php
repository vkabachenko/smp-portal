<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use yii\rbac\Rule;

class ManagePrivateCommentsRule extends Rule
{
    public $name = 'isManagePrivateComments';

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
                && $master->getBidRole() === Bid::TREATMENT_TYPE_PRESALE) {
                return true;
            } else {
                return false;
            }
        }
        return false;

    }

}