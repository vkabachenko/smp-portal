<?php


namespace app\models\form;

use app\models\BidImage;
use yii\base\Model;
use yii\web\UploadedFile;

class MultipleUploadForm extends Model
{
    /**
     * @var UploadedFile[] files uploaded
     */
    public $files = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['files'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'skipOnEmpty' => true, 'maxFiles' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'files' => 'Прикрепить фото',
        ];
    }

    public function upload($attributes)
    {
        foreach ($this->files as $file) {
            $image = new BidImage($attributes);
            $image->file_name = $file->name;
            $image->src_name = \Yii::$app->security->generateRandomString() . '.' . $file->extension;
            if ($image->save()) {
                $file->saveAs($image->getPath());
            } else {
                \Yii::error($image->getErrors());
            }
        }
    }
}