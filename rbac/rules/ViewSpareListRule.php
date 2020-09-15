<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\Spare;
use app\models\User;
use yii\rbac\Rule;

class ViewSpareListRule extends Rule
{
    public $name = 'isViewSpareList';

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
                ) {
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
                ) {
               return true;
           } else {
               return false;
           }

        }

        return false;

    }
}