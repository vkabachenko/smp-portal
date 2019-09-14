<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;
use app\models\Condition;
use yii\jui\DatePicker;
use yii\helpers\Url;
use app\models\Bid;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\RepairStatus;
use app\models\WarrantyStatus;
use app\models\BidStatus;
use app\assets\QuaggaAsset;
use app\models\Master;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
/* @var $commentForm \app\models\form\CommentForm */

QuaggaAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brand_id')->hiddenInput([
        'id' => 'bid-brand-id',
        'data-url' => Url::to(['bid/brand'])
    ])
        ->label(false); ?>

    <?= $form->field($model, 'brand_name')->widget(AutoComplete::class, [
        'clientOptions' => [
            'source' => new JsExpression('
                function (request, response) {
                    $.ajax({
                        beforeSend: function() {
                            $("#bid-brand-id").val("");
                            $("#bid-brand-id").trigger("change");
                        },
                        url: $("#bid-brand-id").data("url"),
                        method: "POST",
                        data: {
                            manufacturerId: $("#bid-manufacturer-id").val(),
                            term: request.term
                        },
                        dataType: "json",
                        success: function(data) {
                            response($.map(data.brands, function (rt) {
                                return {
                                    label: rt.name,
                                    value: rt.id,
                                    manufacturer_id: rt.manufacturer_id
                                };
                            }));
                        },
                        error: function (jqXHR, status) {
                            console.log(status);
                            response([]);
                        }
                    });
                }
            '),
            'select' => new JsExpression('
                function (event, ui) {
                    event.preventDefault();
                    this.value = ui.item.label;

                    $("#bid-brand-id").val(ui.item.value);
                    $("#bid-manufacturer-id").val(ui.item.manufacturer_id);
                }
            '),
        ],
        'options' => [
            'class' => 'form-control',
            'id' => 'bid-brand-name'
        ]
    ]) ?>

    <?= $form->field($model, 'manufacturer_id')
        ->dropDownList(Manufacturer::manufacturersAsMap(),[
            'prompt' => 'Выбор',
            'id' => 'bid-manufacturer-id',
        ]); ?>

    <?= $form->field($model, 'equipment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand_model_id')->hiddenInput([
        'id' => 'bid-brand-model-id',
        'data-url' => Url::to(['bid/brand-model'])
    ])
        ->label(false); ?>

    <?= $form->field($model, 'brand_model_name')->widget(AutoComplete::class, [
        'clientOptions' => [
            'source' => new JsExpression('
                function (request, response) {
                    $.ajax({
                        beforeSend: function() {
                            $("#bid-brand-model-id").val("");
                        },
                        url: $("#bid-brand-model-id").data("url"),
                        method: "POST",
                        data: {
                            brandId: $("#bid-brand-id").val(),
                            term: request.term
                        },
                        dataType: "json",
                        success: function(data) {
                            response($.map(data.brandModels, function (rt) {
                                return {
                                    label: rt.name,
                                    value: rt.id,
                                };
                            }));
                        },
                        error: function (jqXHR, status) {
                            console.log(status);
                            response([]);
                        }
                    });
                }
            '),
            'select' => new JsExpression('
                function (event, ui) {
                    event.preventDefault();
                    this.value = ui.item.label;

                    $("#bid-brand-model-id").val(ui.item.value);
                }
            '),
        ],
        'options' => [
            'class' => 'form-control',
            'id' => 'bid-brand-model-name'
        ]
    ]) ?>

    <?= $form->field($model, 'serial_number')->textInput([
            'id' => 'bid-serial-number',
            'maxlength' => true
    ]) ?>

    <div class="form-group">
        <span style="font-weight: bold;">Файл со штрих-кодом серийного номера</span>
        <?= Html::fileInput('', null, ['id' => 'bid-serial-number-file']) ?>
    </div>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'compositionCombined')->hiddenInput([
        'id' => 'bid-composition-id',
        'data-url' => Url::to(['bid/composition'])
    ])
        ->label(false); ?>

    <?= $form->field($model, 'composition_name')->widget(AutoComplete::class, [
        'clientOptions' => [
            'source' => new JsExpression('
                function (request, response) {
                    $.ajax({
                        beforeSend: function() {
                            $("#bid-composition-id").val("");
                        },
                        url: $("#bid-composition-id").data("url"),
                        method: "POST",
                        data: {
                            brandId: $("#bid-brand-id").val(),
                            term: request.term
                        },
                        dataType: "json",
                        success: function(data) {
                            response($.map(data.compositions, function (rt) {
                                return {
                                    label: rt.name,
                                    value: rt.id,
                                };
                            }));
                        },
                        error: function (jqXHR, status) {
                            console.log(status);
                            response([]);
                        }
                    });
                }
            '),
            'select' => new JsExpression('
                function (event, ui) {
                    event.preventDefault();
                    this.value = ui.item.label;

                    $("#bid-composition-id").val(ui.item.value);
                }
            '),
        ],
        'options' => [
            'class' => 'form-control',
            'id' => 'bid-composition-name'
        ]
    ]) ?>

    <?= $form->field($model, 'defect')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'diagnostic')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'repair_status_id')
        ->dropDownList(RepairStatus::repairStatusAsMap(),['prompt' => 'Выбор']); ?>

    <?= $form->field($model, 'warranty_status_id')
        ->dropDownList(WarrantyStatus::warrantyStatusAsMap(),['prompt' => 'Выбор']); ?>

    <?= $form->field($model, 'status_id')
        ->dropDownList(BidStatus::bidStatusAsMap(),['prompt' => 'Выбор']); ?>

    <?= $form->field($model, 'master_id')
        ->dropDownList(Master::mastersAsMap(),['prompt' => 'Выбор']); ?>

    <?php if ($model->isNewRecord): ?>
        <div class="form-group">
            <?= $form->field($commentForm, 'comment')->textarea() ?>
        </div>
        <div class="form-group">
            <?= $form->field($uploadForm, 'files[]')->widget(FileInput::class, [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions'=>['allowedFileExtensions'=>['jpg','jpeg','png'],'showUpload' => false,]
            ])
            ?>
        </div>
    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    $(function(){
            var App = {
            init: function() {
                App.attachListeners();
            },
            config: {
                reader: "code_128",
                length: 10
            },
            attachListeners: function() {
                $("#bid-serial-number-file").on("change", function(e) {
                    if (e.target.files && e.target.files.length) {
                        App.decode(URL.createObjectURL(e.target.files[0]));
                    }
                });
            },
            detachListeners: function() {
                $("#bid-serial-number-file").off("change");
            },
            decode: function(src) {
                var self = this,
                    config = $.extend({}, self.state, {src: src});
    
                Quagga.decodeSingle(config, function(result) {});
            },
            state: {
                inputStream: {
                    size: 800
                },
                locator: {
                    patchSize: "medium",
                    halfSample: false
                },
                numOfWorkers: 1,
                decoder: {
                    readers: [{
                        format: "code_128_reader",
                        config: {}
                    }]
                },
                locate: true,
                src: null
            }
        };
    
        App.init();
    
        Quagga.onProcessed(function(result) {
            console.log('processed');
            console.log(result);
        });
    
        Quagga.onDetected(function(result) {
            console.log('detected');
            console.log(result);
            var code = result.codeResult.code;
            if (code) {
                $('#bid-serial-number').val(code);
            }
        });
        
        
        $('#bid-manufacturer-id').change(function(){
              $('#bid-brand-id').val('');
              $('#bid-brand-name').val('');
              $('#bid-brand-id').trigger('change');
        });
        
        $('#bid-brand-id').change(function(){
            if ($('#bid-brand-model-id').val()) {
              $('#bid-brand-model-id').val('');
              $('#bid-brand-model-name').val('');
            }
            if ($('#bid-composition-id').val()) {
              $('#bid-composition-id').val('');
              $('#bid-composition-name').val('');
            }
        });
    });
JS;

$this->registerJs($script);
