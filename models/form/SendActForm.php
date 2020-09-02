<?php


namespace app\models\form;


use app\helpers\common\MailHelper;
use app\models\Bid;
use app\models\BidImage;
use app\models\Manager;
use app\models\Master;
use app\models\TemplateModel;
use app\templates\email\EmailTemplate;
use app\templates\excel\act\ExcelAct;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\User;

class SendActForm extends Model
{
    /* @var User */
    public $user;
    public $bidId;
    public $images = [];
    public $sent = [];
    public $email;
    public $subType;

    /* @var ExcelAct */
    public $act;

    /* @var TemplateModel */
    public $template;

    /* @var EmailTemplate */
    public $emailTemplate;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $models = BidImage::getAllowedImages($this->bidId, $this->user);
        $this->sent = [];
        foreach ($models as $model) {
            /* @var $model BidImage */
            $this->images[$model->id] = EasyThumbnailImage::thumbnailImg($model->getPath(), 50, 50);
            $this->sent[] = $model->sent;
        }
        $this->act = new ExcelAct($this->bidId, $this->subType);
        $this->email = $this->getStoredEmail();

        $bid = Bid::findOne($this->bidId);
        $this->template = TemplateModel::find()
            ->where(['agency_id' => $bid->getAgency()->id, 'type' => TemplateModel::TYPE_ACT, 'sub_type' => $this->subType])
            ->one();

        $this->emailTemplate = new EmailTemplate($this->bidId);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bidId', 'email'], 'required'],
            [['images'], 'safe'],
            [['email'], 'string'],
            [['email'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bidId' => 'BidIs',
            'images' => 'Выберите фотографии',
            'email' => 'Email'
        ];
    }

    public function send(UploadExcelTemplateForm $uploadForm)
    {
        $this->setUploadedAct($uploadForm);
        $to = preg_split("/,[\s]*/", $this->email);

        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = \Yii::$app->mailer;
        $message = $mailer->compose();

        $message->setFrom(\Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject($this->getSubject())
            ->setTextBody($this->getMailContent());


        if (file_exists($this->act->getPath())) {
            $message->attach($this->act->getPath());
        } else {
            return false;
        }

        if ($this->images) {
            foreach ($this->images as $index=>$imageId) {
                $model = BidImage::findOne($imageId);
                $imageName = sprintf('photo_%s_%s', $model->bid_id, $index + 1);
                $message->attach($model->getPath(), ['fileName' => $imageName]);
            }
        }

        $result = $message->send();

        if ($result && $this->images) {
            foreach ($this->images as $imageId) {
                $model = BidImage::findOne($imageId);
                $model->sent = true;
                $model->save();
            }
        }
        
        return $result;
    }

    private function getStoredEmail() {
        $bid = Bid::findOne($this->bidId);
        $workshop = $bid->workshop;
        $agency = $bid->getAgency();
        /* @var $master Master */
        $master = Master::findByUserId($this->user->id);
        if ($master) {
            return $agency ? $this->getEmailsList($workshop->email2, $agency->email2, $agency->email4) : '';
        } else {
            /* @var $manager Manager */
            $manager = Manager::findByUserId($this->user->id);
            if ($manager) {
                return $this->getEmailsList($agency->email2, $workshop->email2);
            } else {
                return '';
            }
        }
    }

    private function setUploadedAct(UploadExcelTemplateForm $uploadForm)
    {
        $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
        if ($uploadForm->file) {
            $uploadForm->file->saveAs($this->act->getPath());
        }
    }

    private function getMailContent()
    {
        $body = $this->emailTemplate->getText(strval($this->template->email_body));
        $signature = $this->emailTemplate->getText(strval($this->template->email_signature));

        return sprintf("%s\n\n-------\n%s", $body, $signature);
    }

    private function getEmailsList(...$emails)
    {
        return MailHelper::getEmailsList(...$emails);
    }

    private function getSubject()
    {
        return $this->emailTemplate->getText(strval($this->template->email_subject));
    }

}