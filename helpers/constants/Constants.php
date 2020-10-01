<?php


namespace app\helpers\constants;


class Constants
{
    const BOOLEAN = [
         1 => 'Да',
         0 => 'Нет'
    ];

    const EMPTY_VALUE_ID = 9999999;
    const EMPTY_VALUE = 'Пусто';
    const EMPTY_ELEMENT = [self::EMPTY_VALUE_ID => self::EMPTY_VALUE];

}