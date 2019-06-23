<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;
use app\models\Condition;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'manufacturer_id')
        ->dropDownList(Manufacturer::manufacturersAsMap(),[
            'prompt' => 'Выбор',
            'class' => 'form-control bid-manufacturer',
            'data-url' => Url::to(['bid/brands'])
        ]); ?>

    <?= $form->field($model, 'brand_id')
        ->dropDownList([],[
            'prompt' => 'Выбор',
            'class' => 'form-control bid-brand',
            'data-url-model' => Url::to(['bid/brand-models']),
            'data-url-composition' => Url::to(['bid/compositions'])
        ]); ?>

    <?= $form->field($model, 'brand_model_id')
        ->dropDownList([],['prompt' => 'Выбор', 'class' => 'form-control bid-brand-model']); ?>

    <?= $form->field($model, 'brand_model_name')->textInput(['maxlength' => true, 'class' => 'form-control bid-brand-model-original']) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'composition_id')
        ->dropDownList([],['prompt' => 'Выбор', 'class' => 'form-control bid-brand-composition']); ?>

    <?= $form->field($model, 'composition_name')->textInput(['maxlength' => true, 'class' => 'form-control bid-brand-composition-original']) ?>

    <?= $form->field($model, 'condition_id')
        ->dropDownList(Condition::conditionsAsMap(),['prompt' => 'Выбор', 'class' => 'form-control bid-condition']); ?>

    <?= $form->field($model, 'client_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'client_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_address')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'treatment_type')->dropDownList([ 'warranty' => 'Гарантия', 'pre-sale' => 'Предпродажа', ], ['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'purchase_date')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'application_date')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'warranty_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bid_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bid_1C_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bid_manufacturer_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    $('.bid-manufacturer').change(function(){
        var manufacturerId = $(this).val();
        var brand = $('.bid-brand');
        brand.val('');
        $('.bid-brand-model').val('');
        $('.bid-brand-composition').val('');
        brand.find('option').remove();
        $('<option>').val('').text('Выбор').appendTo(brand);
        if (manufacturerId) {
            $.ajax({
                url: $(this).data('url'),
                method: 'POST',
                data: {
                    manufacturerId: manufacturerId
                }
        })
        .done(function(brands) {
            for(var el of brands) {
               $('<option>').val(el.id).text(el.name).appendTo(brand); 
            }
        })
        .fail(function() {
            console.log('fail')
        });
        }
    });

    $('.bid-brand').change(function(){
        var brandId = $(this).val();
        
        var brandModel = $('.bid-brand-model');
        brandModel.val('');
        brandModel.find('option').remove();
        $('<option>').val('').text('Выбор').appendTo(brandModel);
        
        var brandComposition = $('.bid-brand-composition');
        brandComposition.val('');
        brandComposition.find('option').remove();
        $('<option>').val('').text('Выбор').appendTo(brandComposition);
        
        if (brandId) {
            $.when(
                $.ajax({
                    url: $(this).data('url-model'),
                    method: 'POST',
                    data: {
                        brandId: brandId
                    }
                }),
                $.ajax({
                    url: $(this).data('url-composition'),
                    method: 'POST',
                    data: {
                        brandId: brandId
                    }
                })
            )
            .then(
                function(result1, result2) {

                    var brandModels = result1[0]; 
                    var compositions = result2[0]; 
                    
                    for(var el of brandModels) {
                       $('<option>').val(el.id).text(el.name).appendTo(brandModel); 
                    } 
                    for(var elt of compositions) {
                       $('<option>').val(elt.source + '-' + elt.id).text(elt.name).appendTo(brandComposition); 
                    } 
                },
                function() {
                    console.log('fail')
                }
            );
        }
    });

JS;

$this->registerJs($script);
