<?php


namespace app\rbac\rules;


use app\models\AgencyWorkshop;
use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use yii\rbac\Rule;

class SendActRule extends Rule
{
    public $name = 'isSendAct';

    public function execute($user, $item, $params)
    {
        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);

        if (!$bid || !$bid->manufacturer_id) {
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
                && ($bid->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)
                || $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP))) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($bid->status_id === BidStatus::getId(BidStatus::STATUS_DONE)) {
                return false;
            }
            if ($bid->getAgency()->is_independent) {
                return false;
            }
            /* @var $manager \app\models\Manager */
            $manager = $user->manager;
            if ($manager) {
                if (AgencyWorkshop::getActive($manager->agency, $bid->workshop)
                    && $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_AGENCY)) {
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