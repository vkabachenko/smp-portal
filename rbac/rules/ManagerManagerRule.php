<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\Manager;

use yii\rbac\Rule;

class ManagerManagerRule extends Rule
{
    public $name = 'isManagerManager';

    public function execute($user, $item, $params)
    {
        /* @var $manager \app\models\Manager */
        $manager = Manager::findByUserId($user);

        if (is_null($manager)) {
            return false;
        }

        if (!$manager->main) {
            return false;
        }

        if ($manager->agency_id == $params['agencyId']) {
            return true;
        } else {
            return false;
        }
    }

}