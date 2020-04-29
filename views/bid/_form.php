<?php

use vkabachenko\filepond\widget\FilepondWidget;
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
use app\assets\QuaggaAsset;
use app\models\Master;
use app\helpers\bid\HintHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
/* @var $hints array */

QuaggaAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brand_id')->hiddenInput([
        'id' => 'bid-brand-id',
        'data-url' => Url::to(['bid/brand'])
    ])
        ->label(false); ?>

    <?= $form->field($model, 'brand_name')
        ->widget(AutoComplete::class, [
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

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_model_name'])): ?>
        <?= $form->field($model, 'brand_model_id')->hiddenInput([
            'id' => 'bid-brand-model-id',
            'data-url' => Url::to(['bid/brand-model'])
        ])
            ->label(false); ?>


        <?= $form->field($model, 'brand_model_name',
            ['labelOptions' => HintHelper::getLabelOptions('brand_model_name', $hints)]
            )
            ->widget(AutoComplete::class, [
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
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'serial_number'])): ?>
        <?= $form->field($model, 'serial_number',
            ['labelOptions' => HintHelper::getLabelOptions('serial_number', $hints)]
        )->textInput([
            'id' => 'bid-serial-number',
            'maxlength' => true
        ]) ?>

        <div class="form-group">
            <span style="font-weight: bold;">Файл со штрих-кодом серийного номера</span>
            <?= Html::fileInput('', null, ['id' => 'bid-serial-number-file']) ?>
        </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])): ?>
        <?= $form->field($model, 'vendor_code',
                ['labelOptions' => HintHelper::getLabelOptions('vendor_code', $hints)]
            )->textInput(['maxlength' => true]) ?>
        <?php endif; ?>


    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'composition_name'])): ?>
        <?= $form->field($model, 'compositionCombined')->hiddenInput([
            'id' => 'bid-composition-id',
            'data-url' => Url::to(['bid/composition'])
        ])
            ->label(false); ?>

        <?= $form->field($model, 'composition_name',
                ['labelOptions' => HintHelper::getLabelOptions('composition_name', $hints)]
            )->widget(AutoComplete::class, [
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
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect'])): ?>
        <?= $form->field($model, 'defect',
                ['labelOptions' => HintHelper::getLabelOptions('defect', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (!\Yii::$app->user->can('master')): ?>
    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect_manufacturer'])): ?>
        <?= $form->field($model, 'defect_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('defect_manufacturer', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty_defect'])): ?>
        <?= $form->field($model, 'is_warranty_defect')
            ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_warranty_defect', $hints)]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_repair_possible'])): ?>
        <?= $form->field($model, 'is_repair_possible')
            ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_repair_possible', $hints)]); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_for_warranty'])): ?>
        <?= $form->field($model, 'is_for_warranty')
            ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_for_warranty', $hints)]); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic'])): ?>
        <?= $form->field($model, 'diagnostic',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic', $hints)]
            )->textarea() ?>
    <?php endif; ?>

    <?php if (!\Yii::$app->user->can('master')): ?>
    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic_manufacturer'])): ?>
        <?= $form->field($model, 'diagnostic_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic_manufacturer', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_id'])): ?>
        <?= $form->field($model, 'condition_id',
                ['labelOptions' => HintHelper::getLabelOptions('condition_id', $hints)]
            )
            ->dropDownList(Condition::conditionsAsMap(),['prompt' => 'Выбор', 'class' => 'form-control bid-condition']); ?>
    <?php endif; ?>

    <?= $form->field($model, 'client_type')->dropDownList(Bid::CLIENT_TYPES, ['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'client_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'client_name')->textInput(['maxlength' => true]) ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_phone'])): ?>
        <?= $form->field($model, 'client_phone',
                ['labelOptions' => HintHelper::getLabelOptions('client_phone', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_address'])): ?>
        <?= $form->field($model, 'client_address',
                ['labelOptions' => HintHelper::getLabelOptions('client_address', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])): ?>
        <?= $form->field($model, 'treatment_type',
                ['labelOptions' => HintHelper::getLabelOptions('treatment_type', $hints)]
            )->dropDownList(Bid::TREATMENT_TYPES, ['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'saler_name'])): ?>
        <?= $form->field($model, 'saler_name',
                ['labelOptions' => HintHelper::getLabelOptions('saler_name', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'purchase_date'])): ?>
        <?= $form->field($model, 'purchase_date',
                ['labelOptions' => HintHelper::getLabelOptions('purchase_date', $hints)]
            )->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>
    <?php endif; ?>

    <?php if (!\Yii::$app->user->can('master')): ?>
    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_manufacturer'])): ?>
        <?= $form->field($model, 'date_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('date_manufacturer', $hints)]
            )->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion'])): ?>
        <?= $form->field($model, 'date_completion',
                ['labelOptions' => HintHelper::getLabelOptions('date_completion', $hints)]
            )->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_number'])): ?>
        <?= $form->field($model, 'warranty_number',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_number', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_1C_number'])): ?>
        <?= $form->field($model, 'bid_1C_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_1C_number', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (!\Yii::$app->user->can('master')): ?>
    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_manufacturer_number'])): ?>
        <?= $form->field($model, 'bid_manufacturer_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_manufacturer_number', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])): ?>
        <?= $form->field($model, 'repair_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('repair_status_id', $hints)]
            )
            ->dropDownList(RepairStatus::repairStatusAsMap(),['prompt' => 'Выбор']); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_recommendations'])): ?>
        <?= $form->field($model, 'repair_recommendations',
                ['labelOptions' => HintHelper::getLabelOptions('repair_recommendations', $hints)]
            )->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])): ?>
        <?= $form->field($model, 'warranty_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_status_id', $hints)]
            )
            ->dropDownList(WarrantyStatus::warrantyStatusAsMap(),['prompt' => 'Выбор']); ?>
    <?php endif; ?>


    <?= $form->field($model, 'decision_workshop_status_id',
        ['labelOptions' => HintHelper::getLabelOptions('decision_workshop_status_id', $hints)]
    )
        ->dropDownList(\app\models\DecisionWorkshopStatus::decisionWorkshopStatusAsMap(),['prompt' => 'Выбор']); ?>

    <?php if (\Yii::$app->user->can('admin')): ?>
        <?= $form->field($model, 'decision_agency_status_id',
            ['labelOptions' => HintHelper::getLabelOptions('decision_agency_status_id', $hints)]
        )
            ->dropDownList(\app\models\DecisionAgencyStatus::decisionAgencyStatusAsMap(),['prompt' => 'Выбор']); ?>
    <?php endif; ?>


    <?= $form->field($model, 'master_id',
            ['labelOptions' => HintHelper::getLabelOptions('master_id', $hints)]
        )
        ->dropDownList(Master::mastersAsMap(\Yii::$app->user->identity),['prompt' => 'Выбор']); ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment'])): ?>
        <?= $form->field($model, 'comment',
                ['labelOptions' => HintHelper::getLabelOptions('comment', $hints)]
            )->textarea(); ?>
    <?php endif; ?>

    <?php if ($model->isNewRecord): ?>
        <div class="form-group">
            <?= Html::label('Загрузить фотографии', null, ['class' => 'control-label']) ?>
            <?= FilepondWidget::widget([
                'filepondClass' => 'load-bid-images',
                'model' => $uploadForm,
                'attribute' => 'files[]',
                'multiple' => true,
                'instanceOptions' => [
                    'allowFileTypeValidation' => true,
                    'acceptedFileTypes' => ['image/*']
                ]
            ]);
            ?>
        </div>
    <?php endif; ?>


    <div class="form-group">
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Сохранить', ['name' => 'save', 'class' => 'btn btn-success', 'value' => '1']) ?>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Отправить', ['name' => 'send', 'class' => 'btn btn-primary', 'value' => '1']) ?>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Работы', ['name' => 'job', 'class' => 'btn btn-primary', 'value' => '1']) ?>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Запчасти', ['name' => 'spare', 'class' => 'btn btn-primary', 'value' => '1']) ?>
        </div>
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
