<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ReplacementPart */
?>

<div>

    <?php $form = ActiveForm::begin([
            'id' => 'bid-replacement-part-form',
            'action' => isset($action) ? $action : ''
    ]); ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'vendor_code_replacement')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'is_agree')->checkbox() ?>
    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'price') ?>
    <?= $form->field($model, 'quantity') ?>
    <?= $form->field($model, 'total_price') ?>
    <?= $form->field($model, 'manufacturer')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'link1C')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'comment')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'status')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'is_to_buy')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



