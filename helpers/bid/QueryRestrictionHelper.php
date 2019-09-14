<?php


namespace app\helpers\bid;


use app\models\Bid;
use app\models\Master;
use app\models\User;

class QueryRestrictionHelper
{
    public static function getRestrictions(User $user)
    {
        $master = Master::find()->where(['user_id' => $user->id])->one();

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

}