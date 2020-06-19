<?php

namespace app\models\form;

use app\helpers\common\DateHelper;
use app\helpers\common\MailHelper;
use app\models\Bid;
use app\models\BidImage;
use app\models\Report;
use app\templates\excel\act\ExcelAct;
use yii\base\Model;
use yii\swiftmailer\Message;

class SendReportForm extends Model
{
    /* @var Report */
    public $report;

    public $isPhotosSend = false;

    public $isActsSend = false;

    public function __construct($id, $config = [])
    {
        parent::__construct($config);
        $this->report = Report::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isPhotosSend', 'isActsSend'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'isPhotosSend' => 'Отсылать фото заявок',
            'isActsSend' => 'Отсылать акты заявок',
        ];
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

        if ($this->isPhotosSend) {
            $this->attachPhotos($message);
        }

        if ($this->isActsSend) {
            $this->attachActs($message);
        }

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

    private function attachPhotos(Message $message)
    {
        $images = BidImage::find()
            ->joinWith('bid', false)
            ->where(['bid.report_id' => $this->report->id])
            ->all();
        foreach ($images as $index=>$model) {
            /* @var $model \app\models\BidImage */
            $imageName = sprintf('photo_%s_%s', $model->bid_id, $index + 1);
            $message->attach($model->getPath(), ['fileName' => $imageName]);
        }
    }

    private function attachActs(Message $message)
    {
        $bids = Bid::find()
            ->where(['report_id' => $this->report->id])
            ->all();
        foreach ($bids as $bid) {
            $act = new ExcelAct(['id' => $bid->id]);
            if (file_exists($act->getPath())) {
                $message->attach($act->getPath());
            }
        }
    }

}