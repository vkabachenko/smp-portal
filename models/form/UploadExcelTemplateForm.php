<?php


namespace app\models\form;

use app\models\BidImage;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadExcelTemplateForm extends Model
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
            [['file'], 'file', 'extensions' => ['xlsx'], 'skipOnEmpty' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Прикрепить шаблон excel',
        ];
    }
}