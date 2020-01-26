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

    <?= $form->field($model, 'jobs_section_id')
        ->dropDownList(\app\models\JobsSection::jobsSectionAsMap($model->agency_id),[
            'prompt' => 'Выбор',
        ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'hour_tariff')->textInput(['maxlength' => true, 'id' => 'catalog-hour-tariff']) ?>
    <?= $form->field($model, 'hours_required')->textInput(['maxlength' => true, 'id' => 'catalog-hours-required']) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'id' => 'catalog-price']) ?>
    <?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {
    function getPrice(tariff, hours) {
        var price = tariff * hours;
        return isNaN(price) ? '' : price.toFixed();
    }
    
    function getNumber(strVar) {
        return parseFloat(strVar);
    }
    
    function performCalc() {
        var tariff = getNumber($('#catalog-hour-tariff').val());
        var hours = getNumber($('#catalog-hours-required').val());
        $('#catalog-price').val(getPrice(tariff, hours));
    }
    
    $('#catalog-hour-tariff').change(function() {
        performCalc();
    });
    
    $('#catalog-hours-required').change(function() {
        performCalc();
    });
});
JS;

$this->registerJs($script);


