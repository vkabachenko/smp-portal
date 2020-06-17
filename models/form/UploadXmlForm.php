<?php


namespace app\models\form;

use app\models\BidImage;
use app\services\xml\BaseService;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadXmlForm extends Model
{
    /**
     * @var UploadedFile file uploaded
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => ['xml'], 'skipOnEmpty' => true, 'maxFiles' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'files' => 'Закачать xml файл',
        ];
    }

    public function upload($filename)
    {
        $service = new BaseService($filename);
        $this->file->saveAs($service->filename);
    }
}