<?php


namespace app\helpers\common;


class DateHelper
{
    public static function convert($date)
    {
        if (strpos($date, '.') > -1) {
            $dt = \DateTime::createFromFormat('d.m.Y', $date);
            return $dt->format('Y-m-d');
        } else {
            return $date;
        }
    }

    public static function getReadableDate($date)
    {
        if (empty($date)) {
            return '';
        } else {
            return \Yii::$app->formatter->asDate($date);
        }
    }

}