<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BidAttribute;

/* @var $this yii\web\View */
/* @var $model app\models\BidAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'attribute')->dropDownList(BidAttribute::getEnabledAttributes()) ?>
    <?php endif ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_disabled_agencies')->checkbox() ?>

    <?= $form->field($model, 'is_disabled_workshops')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
