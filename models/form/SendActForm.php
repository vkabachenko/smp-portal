<?php


namespace app\models\form;


use app\helpers\common\MailHelper;
use app\models\Bid;
use app\models\BidImage;
use app\models\DecisionStatusInterface;
use app\models\Manager;
use app\models\Master;
use app\models\TemplateModel;
use app\templates\email\EmailActTemplate;
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
    public $label = [];
    public $email;

    /* @var Bid */
    public $bid;

    /* @var ExcelAct */
    public $act;

    /* @var EmailTemplate */
    public $emailTemplate;

    /* @var DecisionStatusInterface */
    public $decision;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->bid = Bid::findOne($this->bidId);
        $this->decision = $this->getDecision();
        $this->act = $this->decision ? new ExcelAct($this->bid, $this->decision) : null;
        $this->email = $this->getStoredEmail();

        $models = BidImage::getAllowedImages($this->bidId, $this->user);
        $this->sent = [];
        $this->label = [];
        foreach ($models as $model) {
            /* @var $model BidImage */
            $this->images[$model->id] = EasyThumbnailImage::thumbnailImg($model->getPath(), 50, 50);
            $this->sent[] = $model->sent;
            $this->label[] = $model->file_name;
        }

        $template = TemplateModel::find()
            ->where([
                'agency_id' => $this->bid->getAgency()->id,
                'type' => TemplateModel::TYPE_ACT,
                'sub_type' => $this->decision ? $this->decision->sub_type_act : null
            ])
            ->one();

        $this->emailTemplate = $this->decision ? new EmailActTemplate($this->bid, $template, $this->decision) : null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Не заданы адреса для отправки'],
            ['bid', 'required', 'message' => 'Не задана заявка'],
            ['decision', 'required', 'message' => 'Не задано решение мастерской или представительства'],
            ['decision', 'isActExists'],
            [['images', 'act', 'emailTemplate'], 'safe'],
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

    public function isActExists($attribute, $params, $validator)
    {
        if (!empty($this->decision->sub_type_act) && empty($this->act->template)) {
                $this->addError('act', 'Не задан шаблон акта');
        }
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
            ->setSubject($this->emailTemplate->getSubject())
            ->setTextBody($this->emailTemplate->getMailContent());

        if ($this->act->template) {
            if ($this->act->isGenerated()) {
                $message->attach($this->act->getPath());
            } else {
                return false;
            }
        }

        if ($this->images) {
            foreach ($this->images as $index=>$imageId) {
                $model = BidImage::findOne($imageId);
                $message->attach($model->getPath(), ['fileName' => $model->file_name]);
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

    private function getEmailsList(...$emails)
    {
        return MailHelper::getEmailsList(...$emails);
    }

    private function getDecision()
    {
        if (!$this->bid) {
            return null;
        }
        $userModel = $this->user->identity;
        if ($userModel->master) {
            return $this->bid->decisionWorkshopStatus;
        } elseif ($userModel->manager) {
            return $this->bid->decisionAgencyStatus;
        } else {
            return null;
        }
    }



}