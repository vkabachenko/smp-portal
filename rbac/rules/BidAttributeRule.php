<?php


namespace app\rbac\rules;


use app\models\BidAttribute;
use app\models\Manager;
use app\models\Master;
use yii\rbac\Rule;

class BidAttributeRule extends Rule
{
    public $name = 'isBidAttribute';

    public function execute($user, $item, $params)
    {
        /* @var $master Master */
        $master = Master::findByUserId($user);
        if ($master) {
            $commonAttributes = BidAttribute::getHiddenAttributes('is_disabled_workshops');
            $ownAttributes = $master->workshop->getBidAttributes();
        } else {
            /* @var $manager Manager */
            $manager = Manager::findByUserId($user);
            if ($manager) {
                $commonAttributes = BidAttribute::getHiddenAttributes('is_disabled_agencies');
                $ownAttributes = $manager->agency->getBidAttributes();
            } else {
                return false;
            }
        }
        $allAttributes = array_merge($commonAttributes, $ownAttributes);

        return !in_array($params['attribute'], $allAttributes);
    }

}