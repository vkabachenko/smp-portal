<?php

namespace app\services\access;

use app\models\Bid;
use app\models\Agency;
use app\models\BidHistory;
use app\models\BidStatus;
use app\models\User;
use app\models\Workshop;

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
        $workshop = Workshop::find()->joinWith('masters', false)->where(['master.user_id' => $this->user->id])->one();

        if (is_null($workshop)) {
            return ['id' => null];
        }

        $restrictionWorkshop = ['workshop_id' => $workshop->id];

        $rules = $workshop->rules;

        if (!isset($rules['paidBid'])) {
            return $restrictionWorkshop;
        }

        if ($rules['paidBid']) {
            return $restrictionWorkshop;
        } else {
            return [
                'and',
                $restrictionWorkshop,
                ['or', ['treatment_type' => Bid::TREATMENT_TYPE_WARRANTY], ['treatment_type' => null]]
            ];
        }
    }

    public function getManagerRestrictions()
    {
        $agency = Agency::find()
            ->with('workshops')
            ->joinWith('managers', false)
            ->where(['manager.user_id' => $this->user->id])
            ->one();

        if (is_null($agency)) {
            return ['id' => null];
        }

        $workshops = array_map(function(Workshop $workshop) { return $workshop->id; }, $agency->workshops);

        return [
                    'and',
                    ['manufacturer_id' => $agency->manufacturer_id],
                    ['workshop_id' => $workshops],
                    ['or', ['treatment_type' => Bid::TREATMENT_TYPE_WARRANTY], ['treatment_type' => null]],
                    ['or', ['<>', 'status_id', BidStatus::getId(BidStatus::STATUS_FILLED)]]
                ];

    }

}