<?php

namespace app\helpers\common;


use app\models\User;

class MailHelper
{
    public static function getEmailsList(...$emails)
    {
        $emails = array_filter(array_unique($emails), function ($email) {
            return !empty($email);
        });
        return implode(', ', $emails);
    }

    public static function getFromEmail(User $user)
    {
        $defaultFrom = \Yii::$app->params['adminEmail'];
        if (!$user->master) {
            return $defaultFrom;
        }

        $workshop = $user->master->workshop;

        if (!$workshop->email3 || !$workshop->mailbox_pass) {
            return $defaultFrom;
        }

        \Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $workshop->mailbox_host ?: 'smtp.mail.ru',
                'username' => $workshop->email3,
                'password' => $workshop->mailbox_pass,
                'port' => $workshop->mailbox_port ?: '465',
                'encryption' => $workshop->mailbox_encryption ?: 'ssl',
            ],
        ]);

        return $workshop->email3;
    }

}