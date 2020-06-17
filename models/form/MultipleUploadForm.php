<?php


namespace app\models\form;

use app\models\BidImage;
use yii\base\Model;
use yii\web\UploadedFile;

class MultipleUploadForm extends Model
{
    /**
     * @var array
     */
    public $files = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['files', 'each', 'rule' => ['string']]
        ];
    }

    public function upload($attributes)
    {
        foreach ($this->files as $fileDir) {
            if (!empty($fileDir)) {
                $path = \Yii::$app->getModule('filepond')->basePath . $fileDir;
                $files = array_diff(scandir($path), ['..', '.']);
                $origFileName = reset($files);
                if (!empty($origFileName)) {
                    $extension = pathinfo($origFileName, PATHINFO_EXTENSION);
                    $image = new BidImage($attributes);
                    $image->file_name = $this->getNewFilename($attributes['bid_id']) . '.' . $extension;
                    $image->src_name = \Yii::$app->security->generateRandomString() . '.' . $extension;
                    copy($path . '/' . $origFileName, $image->getPath());
                    if (!$image->save()) {
                        \Yii::error($image->getErrors());
                    }
                }
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