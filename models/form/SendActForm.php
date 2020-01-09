<?php


namespace app\models\form;


use app\models\Bid;
use app\models\BidImage;
use app\models\Manager;
use app\models\Master;
use app\models\TemplateModel;
use app\templates\excel\act\ExcelAct;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\base\Model;
use yii\web\UploadedFile;

class SendActForm extends Model
{
    public $userId;
    public $bidId;
    public $images = [];
    public $email;

    /* @var ExcelAct */
    public $act;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $models = BidImage::find()->where(['bid_id' => $this->bidId])->all();
        foreach ($models as $model) {
            /* @var $model BidImage */
            $this->images[$model->id] = EasyThumbnailImage::thumbnailImg($model->getPath(), 50, 50);
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
            [['email'], 'email'],
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

        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = \Yii::$app->mailer;
        $message = $mailer->compose();

        $message->setFrom(\Yii::$app->params['adminEmail'])
            ->setTo($this->email)
            ->setSubject('Акт')
            ->setTextBody($this->getMailContent());


        $message->attach($this->act->getPath());

        if ($this->images) {
            foreach ($this->images as $imageId) {
                $model = BidImage::findOne($imageId);
                $message->attach($model->getPath());
            }
        }
        
        return $message->send();
    }

    private function getStoredEmail() {
        $bid = Bid::findOne($this->bidId);
        $workshop = $bid->workshop;
        $agency = $bid->getAgency();
        /* @var $master Master */
        $master = Master::findByUserId($this->userId);
        if ($master) {
            return $agency ? $agency->email2 : '';
        } else {
            /* @var $manager Manager */
            $manager = Manager::findByUserId($this->userId);
            if ($manager) {
                return $workshop->email2;
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
}