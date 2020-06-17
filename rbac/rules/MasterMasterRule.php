<?php


namespace app\rbac\rules;

use app\models\Master;

use yii\rbac\Rule;

class MasterMasterRule extends Rule
{
    public $name = 'isMasterMaster';

    public function execute($user, $item, $params)
    {
        /* @var $master \app\models\Master */
        $master = Master::findByUserId($user);

        if (is_null($master)) {
            return false;
        }

        if (!$master->main) {
            return false;
        }

        if ($master->workshop_id == $params['workshopId']) {
            return true;
        } else {
            return false;
        }
    }

}