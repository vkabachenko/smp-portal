<?php


namespace app\rbac\rules;


use app\models\Manager;
use yii\rbac\Rule;

class JobsCatalogRule extends Rule
{
    public $name = 'isJobsCatalog';

    public function execute($user, $item, $params)
    {
            /* @var $manager \app\models\Manager */
            $manager = Manager::findByUserId($user);

            if (is_null($manager) || $manager->agency_id != $params['agencyId']) {
                return false;
            } else {
                return true;
            }
    }

}