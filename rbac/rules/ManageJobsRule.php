<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidStatus;
use app\models\JobsCatalog;
use app\models\User;
use yii\rbac\Rule;

class ManageJobsRule extends Rule
{
    public $name = 'isManageJobs';

    public function execute($user, $item, $params)
    {
        /* @var $bid Bid */
        $bid = Bid::findOne($params['bidId']);
        if (is_null($bid)) {
            return false;
        }

        $agency = $bid->getAgency();
        if (is_null($agency)) {
            return false;
        }

        $jobsCatalog = JobsCatalog::find()->where(['agency_id' => $agency->id])->exists();
        if (!$jobsCatalog) {
            return false;
        }

        $userModel = User::findOne($user);
        if (is_null($userModel)) {
            return false;
        }

        if ($userModel->role === 'admin') {
            return true;
        }

        if ($manager = $userModel->manager) {
            return $manager->agency_id == $agency->id && $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_AGENCY);
        }

        if ($master = $userModel->master) {
            return $master->workshop_id == $bid->workshop_id
                && ($bid->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)
                    || $bid->status_id === BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)
                    );
        }

        return false;

    }

}