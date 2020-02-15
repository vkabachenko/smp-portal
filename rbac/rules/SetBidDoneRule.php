<?php


namespace app\rbac\rules;


use app\models\Bid;
use app\models\BidStatus;
use app\models\Workshop;
use yii\rbac\Rule;

class SetBidDoneRule extends Rule
{
    public $name = 'isSetBidDone';

    public function execute($user, $item, $params)
    {
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        if ($bid->status_id !== BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)) {
            return false;
        }

        $workshop = Workshop::find()->joinWith('masters', false)->where(['master.user_id' => $user])->one();

        if (is_null($workshop)) {
            return false;
        }

        if ($bid->workshop_id != $workshop->id) {
            return false;
        }

        return true;

    }

}