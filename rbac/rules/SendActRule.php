<?php


namespace app\rbac\rules;


use app\models\AgencyWorkshop;
use app\models\Bid;
use app\models\BidHistory;
use app\models\User;
use yii\rbac\Rule;

class SendActRule extends Rule
{
    public $name = 'isSendAct';

    public function execute($user, $item, $params)
    {
        $sentBidStatus = BidHistory::sentBidStatus($params['bidId']);

        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);

        if (!$bid || !$bid->manufacturer_id) {
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
                && (is_null($sentBidStatus) || $sentBidStatus == BidHistory::BID_SENT_AGENCY)) {
                return true;
            } else {
                return false;
            }
        } else {
            /* @var $manager \app\models\Manager */
            $manager = $user->manager;
            if ($manager) {
                if (AgencyWorkshop::getActive($manager->agency, $bid->workshop)
                    && $sentBidStatus == BidHistory::BID_SENT_WORKSHOP) {
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