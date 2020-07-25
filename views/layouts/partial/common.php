<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\BackWidget\BackWidget;
use app\widgets\Alert;

?>

        <?= BackWidget::widget([
            'backLink' => isset($this->params['back']) ? $this->params['back'] : null,
            'controller' => $this->context
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
