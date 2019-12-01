<?php


namespace app\rbac\rules;


use app\models\Agency;
use app\models\Bid;
use app\models\Workshop;
use yii\rbac\Rule;

class ManagerBidRule extends Rule
{
    public $name = 'isManagerdBid';

    public function execute($user, $item, $params)
    {
        $agency = Agency::find()
            ->with('workshops')
            ->joinWith('managers', false)
            ->where(['manager.user_id' => $user])
            ->one();

        if (is_null($agency)) {
            return false;
        }

        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $workshops = array_map(function(Workshop $workshop) { return $workshop->id; }, $agency->workshops);

        if (in_array($bid->workshop_id, $workshops)
            && $bid->manufacturer_id = $agency->manufacturer_id
            && boolval($bid->treatment_type) === false) {
            return true;
        } else {
            return false;
        }
    }

}