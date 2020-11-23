<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\JobsCatalog;
use app\models\User;
use yii\rbac\Rule;

class ManageJobs1CRule extends Rule
{
    public $name = 'isManageJobs1C';

    public function execute($user, $item, $params)
    {
        $userModel = User::findOne($user);
        if (is_null($userModel)) {
            return false;
        }

        if ($userModel->role === 'admin') {
            return true;
        }

        if ($userModel->role === 'master') {
            return $userModel->master->getBidRole() === Bid::TREATMENT_TYPE_PRESALE;
        }

        return false;

    }

}