<?php

namespace app\helpers\common;


class CryptHelper
{
    public static function numhash($n) {
        return (((0x0000FFFF & $n) << 16) + ((0xFFFF0000 & $n) >> 16));
    }

}