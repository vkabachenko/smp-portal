<?php

namespace app\models\form;

use app\helpers\common\DateHelper;
use app\helpers\common\MailHelper;
use app\models\Bid;
use app\models\BidImage;
use app\models\Report;
use app\models\TemplateModel;
use app\templates\email\EmailReportTemplate;
use app\templates\email\EmailTemplate;
use app\templates\excel\act\ExcelAct;
use yii\base\Model;
use yii\swiftmailer\Message;

class SendReportForm extends Model
{
    /* @var Report */
    public $report;

    public $isPhotosSend = false;

    public $isActsSend = false;

    /* @var EmailTemplate */
    public $emailTemplate;

    public function __construct($id, $config = [])
    {
        parent::__construct($config);
        $this->report = Report::findOne($id);

        $template = TemplateModel::find()
            ->where(['agency_id' => $this->report->agency_id, 'type' => TemplateModel::TYPE_REPORT])
            ->one();

        $this->emailTemplate = new EmailReportTemplate($this->report, $template);
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
            ->setSubject($this->emailTemplate->getSubject())
            ->setTextBody($this->emailTemplate->getMailContent());

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
            $this->attachAct($bid->id, $message);
        }
    }

    private function attachAct($bidId, Message $message)
    {
        $act = new ExcelAct($bidId, TemplateModel::SUB_TYPE_ACT_DIAGNOSTIC);
        if ($act->isGenerated()) {
            $message->attach($act->getPath());
            return;
        }

        $act = new ExcelAct($bidId, TemplateModel::SUB_TYPE_ACT_WRITE_OFF);
        if ($act->isGenerated()) {
            $message->attach($act->getPath());
            return;
        }

        $act = new ExcelAct($bidId, TemplateModel::SUB_TYPE_ACT_NO_WARRANTY);
        if ($act->isGenerated()) {
            $message->attach($act->getPath());
            return;
        }
    }

}