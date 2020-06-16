<?php


namespace app\models\form;


use app\helpers\common\MailHelper;
use app\models\Bid;
use app\models\BidImage;
use app\models\Manager;
use app\models\Master;
use app\models\TemplateModel;
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

    /* @var ExcelAct */
    public $act;

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
        $this->act = new ExcelAct(['id' => $this->bidId]);
        $this->email = $this->getStoredEmail();
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


        $message->attach($this->act->getPath());

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
        $model = TemplateModel::findByName(TemplateModel::EMAIL_ACT);
        if (is_null($model)) {
            return '';
        } else {
            $fields = $model->fields;
            $content = isset($fields['content']) ? $fields['content'] : '';
            $signature = isset($fields['signature']) ? $fields['signature'] : '';
            return $content . "\n" . "\n" . '-----------------' . "\n" . $signature . "\n";
        }
    }

    private function getEmailsList(...$emails)
    {
        return MailHelper::getEmailsList(...$emails);
    }

    private function getSubject()
    {
        $bid = Bid::findOne($this->bidId);
        $subject = 'Акт технического состояния. ';
        $bidNumber = $bid->bid_number ? 'Заявка № ' . $bid->bid_number . '. ' : ' ';
        $workshopName = 'Мастерская ' . $bid->workshop->name . '. ';
        $agencyName = 'Представительство ' . $bid->getAgency()->name;

        return $subject . $bidNumber . $workshopName . $agencyName;
    }

}