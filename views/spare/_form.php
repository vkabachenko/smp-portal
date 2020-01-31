<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Spare */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput(['id' => 'spare-quantity']) ?>

    <?= $form->field($model, 'price')->textInput(['id' => 'spare-price']) ?>

    <?= $form->field($model, 'total_price')->textInput(['id' => 'spare-total-price']) ?>

    <?= $form->field($model, 'invoice_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_date')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {
    function getTotalPrice(quantity, price) {
        var totalPrice = quantity * price;
        return isNaN(totalPrice) ? '' : totalPrice.toFixed();
    }
    
    function performCalc() {
        var quantity = parseInt($('#spare-quantity').val());
        var price = parseFloat($('#spare-price').val());
        $('#spare-total-price').val(getTotalPrice(quantity, price));
    }
    
    $('#spare-quantity').change(function() {
        performCalc();
    });
    
    $('#spare-price').change(function() {
        performCalc();
    });
});
JS;

$this->registerJs($script);
