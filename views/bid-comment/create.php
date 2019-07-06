<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $model \app\models\BidComment */

$this->title = 'Новый комментарий';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['bid/index']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр  заявки', 'url' => ['bid/view', 'id' => $model->bid_id]];
$this->params['breadcrumbs'][] = ['label' => 'Комментарии', 'url' => ['bid-comment/index', 'bidId' => $model->bid_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>

    <h3><?= $this->title ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'comment')->textarea() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
