<?php


namespace app\widgets\BackWidget;


use app\models\PageHelper;

class BackWidget extends \yii\bootstrap\Widget
{
    public $backLink = null;
    public $controller;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (is_null($this->backLink)) {
            return '';
        } else {
            $pageHelperModel = PageHelper::find()
                ->where(['controller' => $this->controller->id, 'action' => $this->controller->action->id])
                ->one();
            if (!$pageHelperModel) {
                $pageHelperModel = new PageHelper(['controller' => $this->controller->id, 'action' => $this->controller->action->id]);
            }
            return $this->render('index', ['backLink' => $this->backLink, 'pageHelperModel' => $pageHelperModel]);
        }
    }

}