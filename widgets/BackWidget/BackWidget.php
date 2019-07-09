<?php


namespace app\widgets\BackWidget;


class BackWidget extends \yii\bootstrap\Widget
{
    public $backLink = null;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (is_null($this->backLink)) {
            return '';
        } else {
            return $this->render('index', ['backLink' => $this->backLink]);
        }
    }

}