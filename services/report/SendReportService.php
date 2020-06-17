<?php

namespace app\services\report;

use app\helpers\common\DateHelper;
use app\helpers\common\MailHelper;
use app\models\Report;

class SendReportService
{
    /* @var Report */
    private $report;

    public function __construct($id)
    {
        $this->report = Report::findOne($id);
    }

    public function send()
    {
        $to = preg_split("/,[\s]*/", MailHelper::getEmailsList($this->report->agency->email2, $this->report->workshop->email2));

        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = \Yii::$app->mailer;
        $message = $mailer->compose();

        $message->setFrom(\Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject($this->getSubject())
            ->setTextBody($this->getMailContent());


        $message->attach($this->report->report_filename);

        $result = $message->send();

        if ($result) {
            $this->report->is_transferred = true;
            $this->report->save();
        }

        return $result;
    }

    private function getSubject()
    {
        return sprintf('Отчет о выполненных работах № %s от %s. Мастерская %s, представительство %s',
        $this->report->report_nom,
        DateHelper::getReadableDate($this->report->report_date),
        $this->report->workshop->name,
        $this->report->agency->name
        );
    }

    private function getMailContent()
    {
        return 'Отчет находится в приложенном файле';
    }

}