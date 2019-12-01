<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\Manager;

use yii\rbac\Rule;

class ManagerAgencyRule extends Rule
{
    public $name = 'isManagerAgency';

    public function execute($user, $item, $params)
    {
        /* @var $manager \app\models\Manager */
        $manager = Manager::findByUserId($user);
        return true;
        if (is_null($manager)) {
            return false;
        }

        if ($manager->agency_id != $params['agencyId']) {
            return false;
        }

        if ($manager->main) {
            return true;
        } else {
            return false;
        }
    }

}