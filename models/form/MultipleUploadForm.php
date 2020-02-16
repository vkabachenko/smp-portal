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
            $image->file_name = $this->getNewFilename($attributes['bid_id']);
            $image->src_name = \Yii::$app->security->generateRandomString() . '.' . $file->extension;
            if ($image->save()) {
                $file->saveAs($image->getPath());
            } else {
                \Yii::error($image->getErrors());
            }
        }
    }

    private function getNewFilename($bidId)
    {
        $lastImageNumber = BidImage::find()
            ->where(['bid_id' => $bidId])
            ->max('CAST(SUBSTRING([[file_name]], LOCATE("-", [[file_name]]) +1) AS UNSIGNED)');
        $newImageNumber = is_null($lastImageNumber) ? 1 : $lastImageNumber + 1;

        return sprintf('%s-%s', strval($bidId), strval($newImageNumber));
    }
}