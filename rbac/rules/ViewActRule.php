<?php


namespace app\rbac\rules;


use app\models\AgencyWorkshop;
use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use yii\rbac\Rule;

class ViewActRule extends Rule
{
    public $name = 'isViewAct';

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
        if ($master) {
            if ($master->workshop_id == $bid->workshop_id
                && $master->getBidRole() == Bid::TREATMENT_TYPE_WARRANTY) {
                return true;
            } else {
                return false;
            }
        } else {
            /* @var $manager \app\models\Manager */
            $manager = $user->manager;
            if ($manager) {
                if (AgencyWorkshop::getActive($manager->agency, $bid->workshop)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

}