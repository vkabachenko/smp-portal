<?php


namespace app\rbac\rules;

use app\models\Bid;
use app\models\BidAttribute;
use app\models\Manager;
use app\models\Master;
use yii\rbac\Rule;

class BidAttributeRule extends Rule
{
    public $name = 'isBidAttribute';

    public function execute($user, $item, $params)
    {
        $keyCache = isset($params['is_control'])
            ? 'bid-attributes-control' . $user
            : 'bid-attributes-' . $user ;
        $allAttributes = \Yii::$app->cache->get($keyCache);

        if ($allAttributes === false) {
            /* @var $master Master */
            $master = Master::findByUserId($user);
            if ($master) {
                if (isset($params['is_control'])) {
                    $allAttributes = BidAttribute::getHiddenAttributes('is_control');
                } else {
                    $commonAttributes = $master->getBidRole() === Bid::TREATMENT_TYPE_WARRANTY
                        ? BidAttribute::getHiddenAttributes('is_disabled_agencies')
                        : BidAttribute::getHiddenAttributes('is_disabled_workshops');
                    $ownAttributes = $master->workshop->getBidAttributes('bid_attributes');
                    $allAttributes = array_merge($commonAttributes, $ownAttributes);
                }
            } else {
                /* @var $manager Manager */
                $manager = Manager::findByUserId($user);
                if ($manager) {
                    $commonAttributes = BidAttribute::getHiddenAttributes('is_disabled_agencies');
                    $ownAttributes = $manager->agency->getBidAttributes('bid_attributes');
                } else {
                    return false;
                }
                $allAttributes = array_merge($commonAttributes, $ownAttributes);
            }

            \Yii::$app->cache->set($keyCache, $allAttributes);
        }

        return !in_array($params['attribute'], $allAttributes);
    }

}