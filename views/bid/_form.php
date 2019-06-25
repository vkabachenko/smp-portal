<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;
use app\models\Condition;
use yii\jui\DatePicker;
use yii\helpers\Url;
use app\models\Brand;
use app\models\BrandModel;
use app\helpers\bid\CompositionHelper;
use yii\jui\Accordion;
use app\models\Bid;

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
        ->dropDownList(Brand::brandsManufacturerAsMap($model->manufacturer_id),[
            'prompt' => 'Выбор',
            'class' => 'form-control bid-brand',
            'data-url-model' => Url::to(['bid/brand-models']),
            'data-url-composition' => Url::to(['bid/compositions'])
        ]); ?>

    <?= Accordion::widget([
        'id' => 'brand-model-accordion',
        'items' => [
            [
                'header' => 'Модель - выбор из списка',
                'content' => $form->field($model, 'brand_model_id')
                                    ->dropDownList(
                                            BrandModel::brandModelsAsMap($model->brand_id),
                                            ['prompt' => 'Выбор', 'class' => 'form-control bid-brand-model']
                                            )
                                    ->label(false)
            ],
            [
                'header' => 'Модель - ввод названия',
                'content' => $form->field($model, 'brand_model_name')
                                    ->textInput(
                                            ['maxlength' => true, 'class' => 'form-control bid-brand-model-original']
                                    )
                                    ->label(false)
            ]
        ],
        'options' => ['style' => 'margin-bottom: 10px;'],
        'headerOptions' => ['tag' => 'p', 'style' => 'font-size: 80%; font-weight: bold;'],
    ]); ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= Accordion::widget([
        'id' => 'brand-composition-accordion',
        'items' => [
            [
                'header' => 'Комплектность - выбор из списка',
                'content' => $form->field($model, 'compositionCombined')
                    ->dropDownList(
                          CompositionHelper::unionCompositionsAsMap($model->brand_id),[
                                  'prompt' => 'Выбор', 'class' => 'form-control bid-brand-composition'
                          ]
                    )
                    ->label(false)
            ],
            [
                'header' => 'Комплектность - ввод названия',
                'content' => $form->field($model, 'composition_name')
                    ->textInput(['maxlength' => true, 'class' => 'form-control bid-brand-composition-original'])
                    ->label(false)
            ]
        ],
        'options' => ['style' => 'margin-bottom: 10px;'],
        'headerOptions' => ['tag' => 'p', 'style' => 'font-size: 80%; font-weight: bold;'],
    ]); ?>

    <?= $form->field($model, 'condition_id')
        ->dropDownList(Condition::conditionsAsMap(),['prompt' => 'Выбор', 'class' => 'form-control bid-condition']); ?>

    <?= $form->field($model, 'client_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'client_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_address')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'treatment_type')->dropDownList(Bid::TREATMENT_TYPES, ['prompt' => 'Выбор']) ?>

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
    $(function(){
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
        
        $( "#brand-model-accordion" ).accordion( "option", "active", {$model->brandModelTab});
        $( "#brand-composition-accordion" ).accordion( "option", "active", {$model->compositionTab});
    });

JS;

$this->registerJs($script);
