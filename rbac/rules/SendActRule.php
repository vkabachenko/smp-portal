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
        /* @var $bidHistory BidHistory */
        $bidHistory = BidHistory::find()
            ->where(['bid_id' => $params['bidId']])
            ->andWhere(['or', ['action' => BidHistory::BID_SENT_WORKSHOP], ['action' => BidHistory::BID_SENT_AGENCY]])
            ->orderBy('created_at DESC')
            ->one();

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
            if ($master->workshop_id == $bid->workshop_id && (!$bidHistory || $bidHistory->action == BidHistory::BID_SENT_AGENCY)) {
                return true;
            } else {
                return false;
            }
        } else {
            /* @var $manager \app\models\Manager */
            $manager = $user->manager;
            if ($manager) {
                if (AgencyWorkshop::getActive($manager->agency, $bid->workshop)
                    && $bidHistory
                    && $bidHistory->action == BidHistory::BID_SENT_WORKSHOP) {
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