<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\Master;
use app\models\Workshop;
use yii\rbac\Rule;

class ManageRestrictedBidRule extends Rule
{
    public $name = 'isManageRestrictedBid';

    public function execute($user, $item, $params)
    {
        $workshop = Workshop::find()->joinWith('masters', false)->where(['master.user_id' => $user])->one();

        if (is_null($workshop)) {
            return false;
        }

        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        if ($bid->workshop_id != $workshop->id) {
            return false;
        }

        $rules = $workshop->rules;

        if (!isset($rules['paidBid'])) {
            return true;
        }

        if ($rules['paidBid']) {
           return true;
        } else {
            if (!$bid->isPaid()) {
                return true;
            } else {
                return false;
            }
        }

    }

}