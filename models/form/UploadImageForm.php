<?php


namespace app\models\form;

use app\models\BidImage;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'mimeTypes' =>  ['image/*'], 'skipOnEmpty' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Прикрепить изображение',
        ];
    }
}