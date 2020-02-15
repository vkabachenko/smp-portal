<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidStatus;
use app\models\User;
use app\models\Workshop;
use yii\rbac\Rule;

class UpdateDecisionManagerRule extends Rule
{
    public $name = 'isUpdateDecisionManager';

    public function execute($user, $item, $params)
    {
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $user = User::findOne($user);
        if (!$user) {
            return false;
        }

        $agency = $bid->getAgency();
        if (is_null($agency)) {
            return false;
        }

        if ($manager = $user->manager) {
            return $manager->agency_id == $agency->id && $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_AGENCY);
        }

        return false;

    }

}