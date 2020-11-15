<?php

namespace app\commands;

use app\models\Workshop;
use PhpImap\IncomingMail;
use yii\console\Controller;
use app\services\mail\ImapService;


class ImapController extends Controller
{

    /**
     * @param $email
     * @param $dateBegin
     *
     * call: php yii imap/fetch mail@mail.com 01-Jan-2019
     */
    public function actionFetch($email, $dateBegin)
    {
        $this->fetch($email, $dateBegin, function(IncomingMail $mail) { echo strval($mail->id) . "\n"; }, false);
        echo 'Done' . "\n";
    }

    private function fetch($email, $dateBegin, callable $logFunction, $isProcess = true)
    {
        try {
            $service = new ImapService($email);
            $mails = $service->getMails($dateBegin);
            foreach ($mails as $mail) {
                $logFunction($mail);
                if ($isProcess) {
                    $service->processMail($mail);
                }
            }
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }
    }

    public function actionFetchAll()
    {
        $now = date('d-M-Y');
        $workshops = Workshop::find()->all();
        foreach ($workshops as $workshop) {
            /* @var $workshop Workshop */
            \Yii::info('fetching email ' .  $workshop->email3);
            $this->fetch($workshop->email3, $now, function($mail) { \Yii::info($mail->id); });
        }
    }
}
