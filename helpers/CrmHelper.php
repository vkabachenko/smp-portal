<?php


namespace app\helpers;


use yii\db\Expression;
use yii\db\Query;

class CrmHelper
{
    public static function purifyPhone($phone)
    {
        $phone = substr(preg_replace('/\D/', '', $phone), -10);

        return $phone;
//        return strlen($phone) === 10 ? $phone : null;
    }
}