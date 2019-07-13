<?php


namespace app\models\form;


use app\models\BidImage;
use app\templates\excel\act\ExcelActDefault;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\base\Model;

class SendActForm extends Model
{
    public $bidId;
    public $images = [];
    public $email;

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

    public function send()
    {
        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = \Yii::$app->mailer;
        $message = $mailer->compose();

        $message->setFrom(\Yii::$app->params['adminEmail'])
            ->setTo($this->email)
            ->setSubject('Акт')
            ->setTextBody('Test');

        $act = new ExcelActDefault($this->bidId);
        $act->generate();
        $act->save();
        $message->attach($act->getPath());

        foreach ($this->images as $imageId) {
            $model = BidImage::findOne($imageId);
            $message->attach($model->getPath());
        }

        return $message->send();
    }
}