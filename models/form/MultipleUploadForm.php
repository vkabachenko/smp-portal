<?php


namespace app\models\form;

use app\models\BidHistory;
use app\models\Image;
use yii\base\Model;
use yii\web\UploadedFile;

class MultipleUploadForm extends Model
{
    /**
     * @var UploadedFile[] files uploaded
     */
    public $files;

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

    public function upload(BidHistory $model)
    {
        foreach ($this->files as $file) {
            $image = new Image(
                [
                    'bid_id' => $model->bid_id,
                    'bid_history_id' => $model->id,
                    'file_name' => $file->name,
                    'src_name' => \Yii::$app->security->generateRandomString() . '.' . $file->extension
                ]
            );
            if ($image->save()) {
                $file->saveAs($image->getPath());
            } else {
                \Yii::error($image->getErrors());
            }
        }
    }
}