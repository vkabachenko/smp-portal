<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\JobsCatalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_actual')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'jobs_section_id')
        ->dropDownList(\app\models\JobsSection::jobsSectionAsMap($model->agency_id),[
            'prompt' => 'Выбор',
        ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'hour_tariff')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'hours_required')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
