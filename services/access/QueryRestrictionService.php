<?php

namespace app\services\access;

use app\models\Bid;
use app\models\Manager;
use app\models\Master;
use app\models\User;

class QueryRestrictionService
{
    /* @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getRestrictions()
    {
        $role = $this->user->role;
        switch ($role) {
            case 'master':
                return $this->getMasterRestrictions();
            case'manager':
                return $this->getManagerRestrictions();
            default:
                return [];
        }
    }

    public function getMasterRestrictions()
    {
        $master = Master::find()->where(['user_id' => $this->user->id])->one();

        if (is_null($master)) {
            return [];
        }

        $rules = $master->workshop->rules;

        if (!isset($rules['paidBid'])) {
            return [];
        }

        if ($rules['paidBid']) {
            return [];
        } else {
            return ['or', ['treatment_type' => Bid::TREATMENT_TYPE_WARRANTY], ['treatment_type' => null]];
        }
    }

    public function getManagerRestrictions()
    {
        $manager = Manager::find()->where(['user_id' => $this->user->id])->one();

        if (is_null($manager)) {
            return [];
        }

        return [
                    'and',
                    ['manufacturer_id' => $manager->manufacturer_id],
                    [
                        'or', ['treatment_type' => Bid::TREATMENT_TYPE_WARRANTY], ['treatment_type' => null]
                    ]
                ];

    }

}