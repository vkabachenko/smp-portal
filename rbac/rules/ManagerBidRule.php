<?php


namespace app\rbac\rules;


use app\models\Agency;
use app\models\Bid;
use app\models\BidStatus;
use app\models\Workshop;
use yii\rbac\Rule;

class ManagerBidRule extends Rule
{
    public $name = 'isManagerdBid';

    public function execute($user, $item, $params)
    {
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        if ($bid->treatment_type == Bid::TREATMENT_TYPE_PRESALE) {
            return false;
        }

        if ($bid->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)) {
            return false;
        }

        $agency = Agency::find()
            ->with('workshops')
            ->joinWith('managers', false)
            ->where(['manager.user_id' => $user])
            ->one();

        if (is_null($agency)) {
            return false;
        }



        $workshops = array_map(function(Workshop $workshop) { return $workshop->id; }, $agency->workshops);

        if (in_array($bid->workshop_id, $workshops)
            && $bid->manufacturer_id == $agency->manufacturer_id
            ) {
            return true;
        } else {
            return false;
        }
    }

}