<?php


namespace app\rbac\rules;

use app\models\Agency;
use app\models\Manager;

use app\models\Master;
use yii\rbac\Rule;

class JobsCatalogRule extends Rule
{
    public $name = 'isJobsCatalog';

    public function execute($user, $item, $params)
    {
        $master = Master::findByUserId($user);
        if (!is_null($master)) {
            $agencies = $master->workshop->agencies;
            $agenciesId = array_map(function (Agency $agency) { return $agency->id; }, $agencies);
            return in_array($params['agencyId'], $agenciesId);
        } else {
            /* @var $manager \app\models\Manager */
            $manager = Manager::findByUserId($user);

            if (is_null($manager) || $manager->agency_id != $params['agencyId']) {
                return false;
            } else {
                return true;
            }
        }
    }

}