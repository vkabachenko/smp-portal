<?php

namespace app\helpers\common;


class MailHelper
{
    public static function getEmailsList(...$emails)
    {
        $emails = array_filter(array_unique($emails), function ($email) {
            return !empty($email);
        });
        return implode(', ', $emails);
    }

}