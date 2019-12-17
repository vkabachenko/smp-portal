<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workshop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'manufacturer_id')
        ->dropDownList(Manufacturer::manufacturersAsMap(),[
            'prompt' => 'Выбор',
            'disabled' => !$model->isNewRecord
        ]); ?>
    <?= $form->field($model, 'phone1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone4')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email4')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


