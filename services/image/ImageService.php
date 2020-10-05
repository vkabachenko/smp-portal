<?php

namespace app\services\image;

use yii\imagine\Image;

class ImageService
{
    private $path;

    public function __construct($dir, $filename)
    {
        $this->path = $dir . '/' . $filename;
        if (!file_exists($this->path)) {
            throw new \DomainException(sprintf('File %s does not exists', $this->path));
        }
    }

    public function resize()
    {
        $image = Image::getImagine()->open($this->path);

        $originalWidth = $image->getSize()->getWidth();
        $originalHeight = $image->getSize()->getHeight();

        $desirableWidth = min($originalWidth, \Yii::$app->params['maxImageSize']);
        $desirableHeight = min($originalHeight, \Yii::$app->params['maxImageSize']);

        if ($originalWidth == $desirableWidth && $originalHeight == $desirableHeight) {
            return;
        }

        Image::resize($this->path, $desirableWidth, $desirableHeight)
            ->save($this->path);
    }

}