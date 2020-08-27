<?php


namespace app\rbac\rules;

use app\models\BidHistory;
use app\models\User;
use yii\rbac\Rule;

class BidHistoryRule extends Rule
{
    public $name = 'isViewBidHistory';

    public function execute($user, $item, $params)
    {
        $userModel = User::findOne($user);
        $bidHistory = BidHistory::findOne($params['id']);

        if (is_null($bidHistory)) {
            return false;
        }

        if ($userModel->role !== 'manager') {
            return false;
        }

        if ($bidHistory->user->role === 'manager'
            || $bidHistory->action === BidHistory::CREATED
            || $bidHistory->action === BidHistory::IMPORTED_1C
        ) {
            return true;
        } else {
            return false;
        }
    }

}