<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $model \app\models\BidComment */

$this->title = 'Новый комментарий';
$this->params['back'] = ['bid-comment/index', 'bidId' => $model->bid_id];
?>

<div>

    <h3><?= $this->title ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'comment')->textarea() ?>
    </div>

    <?php if (\Yii::$app->user->identity->role != 'manager'): ?>
    <div class="form-group">
        <?= $form->field($model, 'private')->checkbox() ?>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
