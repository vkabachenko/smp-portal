<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\Master;

use yii\rbac\Rule;

class MasterWorkshopRule extends Rule
{
    public $name = 'isMasterWorkshop';

    public function execute($user, $item, $params)
    {
        /* @var $master \app\models\Master */
        $master = Master::findByUserId($user);

        if (is_null($master)) {
            return false;
        }

        if ($master->main) {
            return true;
        } else {
            return false;
        }
    }

}