<?php

use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $backLink array */
/* @var $pageHelperModel \app\models\PageHelper */
?>

<div class="back-section" style="margin-bottom: 10px;">
    <?= \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-arrow-left"><span class="btn-back">Назад</span></i>',
        $backLink,
        ['class' => 'btn btn-danger']) ?>
    <?php if (\Yii::$app->user->can('admin') || !$pageHelperModel->isNewRecord): ?>

        <?php Modal::begin([
            'id' => 'page-helper-modal',
            'header' => '<h3>Помощь по разделу</h3>',
            'toggleButton' => [
                'tag' => 'a',
                'label' => '<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>',
                'style' => 'cursor: pointer; font-size: 24px;'
            ],
        ]);

        echo $this->render('page-helper', ['model' => $pageHelperModel]);

        Modal::end(); ?>

    <?php endif; ?>
</div>
