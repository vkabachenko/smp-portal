<?php


namespace app\helpers\bid;


use app\models\Bid;
use app\models\BidStatus;
use yii\web\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Manufacturer;
use app\models\Condition;
use yii\jui\DatePicker;
use app\models\RepairStatus;
use app\models\WarrantyStatus;
use app\models\Master;
use yii\bootstrap\Html;

class EditHelper
{
    public static function getAttributesEdit(Bid $model, User $user, ActiveForm $form, $hints)
    {
        $attributes = [];

        $attributes['brand_id'] = $form->field($model, 'brand_id')->hiddenInput([
            'id' => 'bid-brand-id',
            'data-url' => Url::to(['bid/brand'])
        ])
            ->label(false);

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_name'])) {
            $attributes['brand_name'] = $form->field($model, 'brand_name')
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
                        'id' => 'bid-brand-name',
                        'disabled' => self::isDisabled($model, 'brand_name')
                    ]
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'manufacturer_id'])) {
            $attributes['manufacturer_id'] = $form->field($model, 'manufacturer_id')
                ->dropDownList(Manufacturer::manufacturersAsMap(), [
                    'prompt' => 'Выбор',
                    'id' => 'bid-manufacturer-id',
                    'disabled' => self::isDisabled($model, 'manufacturer_id')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'equipment'])) {
            $attributes['equipment'] = $form->field($model, 'equipment')
                ->textInput([
                    'maxlength' => true,
                    'disabled' => self::isDisabled($model, 'equipment')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'equipment_manufacturer'])) {
            $attributes['equipment_manufacturer'] = $form->field($model, 'equipment_manufacturer')
                ->textInput([
                    'maxlength' => true,
                    'disabled' => self::isDisabled($model, 'equipment_manufacturer')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_model_name'])) {
            $attributes['brand_model_id'] = $form->field($model, 'brand_model_id')->hiddenInput([
                'id' => 'bid-brand-model-id',
                'data-url' => Url::to(['bid/brand-model'])
            ])
                ->label(false);

            $attributes['brand_model_name'] = $form->field($model, 'brand_model_name',
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
                        'id' => 'bid-brand-model-name',
                        'disabled' => self::isDisabled($model, 'brand_model_name')
                    ]
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'serial_number'])) {
            $attributes['serial_number'] = $form->field($model, 'serial_number',
                ['labelOptions' => HintHelper::getLabelOptions('serial_number', $hints)]
            )->textInput([
                'id' => 'bid-serial-number',
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'serial_number')
            ]);
            $attributes['serial_number'] .= sprintf(
                '<div class="form-group"><span style="font-weight: bold;">Файл со штрих-кодом серийного номера</span>)%s</div>',
                Html::fileInput('', null, [
                    'id' => 'bid-serial-number-file',
                    'disabled' => self::isDisabled($model, 'serial_number')
                    ])
            );
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])) {
            $attributes['vendor_code'] = $form->field($model, 'vendor_code',
                ['labelOptions' => HintHelper::getLabelOptions('vendor_code', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'vendor_code')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'composition_name'])) {
            $attributes['compositionCombined'] = $form->field($model, 'compositionCombined')->hiddenInput([
                'id' => 'bid-composition-id',
                'data-url' => Url::to(['bid/composition'])
            ])
                ->label(false);
            $attributes['composition_name'] = $form->field($model, 'composition_name',
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
                    'id' => 'bid-composition-name',
                    'disabled' => self::isDisabled($model, 'composition_name')
                ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'composition_name_manufacturer'])) {
            $attributes['composition_name_manufacturer'] = $form->field($model, 'composition_name_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('composition_name_manufacturer', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'composition_name_manufacturer')
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'author'])) {
            $attributes['author'] = $form->field($model, 'author',
                ['labelOptions' => HintHelper::getLabelOptions('author', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'author')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'sum_manufacturer'])) {
            $attributes['sum_manufacturer'] = $form->field($model, 'sum_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('sum_manufacturer', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'sum_manufacturer')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'subdivision'])) {
            $attributes['subdivision'] = $form->field($model, 'subdivision',
                ['labelOptions' => HintHelper::getLabelOptions('subdivision', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'subdivision')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect'])) {
            $attributes['defect'] = $form->field($model, 'defect',
                ['labelOptions' => HintHelper::getLabelOptions('defect', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'defect')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect_manufacturer'])) {
            $attributes['defect_manufacturer'] = $form->field($model, 'defect_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('defect_manufacturer', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'defect_manufacturer')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_control'])) {
            $attributes['is_control'] = $form->field($model, 'is_control')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_control', $hints),
                    'disabled' => self::isDisabled($model, 'is_control')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_report'])) {
            $attributes['is_report'] = $form->field($model, 'is_report')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_report', $hints),
                    'disabled' => self::isDisabled($model, 'is_report')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_reappeal'])) {
            $attributes['is_reappeal'] = $form->field($model, 'is_reappeal')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_reappeal', $hints),
                    'disabled' => self::isDisabled($model, 'is_reappeal')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'document_reappeal'])) {
            $attributes['document_reappeal'] = $form->field($model, 'document_reappeal',
                ['labelOptions' => HintHelper::getLabelOptions('document_reappeal', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'document_reappeal')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty'])) {
            $attributes['is_warranty'] = $form->field($model, 'is_warranty')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_warranty', $hints),
                    'disabled' => self::isDisabled($model, 'is_warranty')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty_defect'])) {
            $attributes['is_warranty_defect'] = $form->field($model, 'is_warranty_defect')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_warranty_defect', $hints),
                    'disabled' => self::isDisabled($model, 'is_warranty_defect')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_comment'])) {
            $attributes['warranty_comment'] = $form->field($model, 'warranty_comment',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_comment', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'warranty_comment')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_repair_possible'])) {
            $attributes['is_repair_possible'] = $form->field($model, 'is_repair_possible')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_repair_possible', $hints),
                    'disabled' => self::isDisabled($model, 'is_repair_possible')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_for_warranty'])) {
            $attributes['is_for_warranty'] = $form->field($model, 'is_for_warranty')
                ->checkbox([
                    'labelOptions' => HintHelper::getLabelOptions('is_for_warranty', $hints),
                    'disabled' => self::isDisabled($model, 'is_for_warranty')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic'])) {
            $attributes['diagnostic'] = $form->field($model, 'diagnostic',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic', $hints)]
            )->textarea([
                'disabled' => self::isDisabled($model, 'diagnostic')
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic_manufacturer'])) {
            $attributes['diagnostic_manufacturer'] = $form->field($model, 'diagnostic_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic_manufacturer', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'diagnostic_manufacturer')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_id'])) {
            $attributes['condition_id'] = $form->field($model, 'condition_id',
                ['labelOptions' => HintHelper::getLabelOptions('condition_id', $hints)]
            )
                ->dropDownList(Condition::conditionsAsMap(),[
                    'prompt' => 'Выбор',
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'condition_id')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_manufacturer_id'])) {
            $attributes['condition_manufacturer_id'] = $form->field($model, 'condition_manufacturer_id',
                ['labelOptions' => HintHelper::getLabelOptions('condition_manufacturer_id', $hints)]
            )
                ->dropDownList(Condition::conditionsAsMap(),[
                    'prompt' => 'Выбор',
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'condition_manufacturer_id')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_id'])) {
            $attributes['client_id'] = $form
                ->field($model,
                    'client_id',
                    [
                        'options' => ['class' => ''],
                        'inputOptions' => [
                            'id' => 'client_id',
                            'data-url' => Url::to(['client/auto-complete']),
                            'data-select' => Url::to(['client/update-modal'])
                        ]
                    ]
                )
                ->hiddenInput();

            $clientValue = $model->client_id ? $model->client->name : '';
            if (\Yii::$app->user->can('updateClient')) {
                $btnSaveClient = Html::a('Редактировать', '#', [
                        'class' => 'btn btn-success client-modal-btn',
                        'disabled' => self::isDisabled($model, 'client_id')
                    ]) .
                    ' ' .
                    Html::a('Новый клиент', '#', [
                        'class' => 'btn btn-success new-client-modal-btn',
                        'disabled' => self::isDisabled($model, 'client_id')
                    ]);
            } else {
                $btnSaveClient = '';
            }

            $autocompleteClient = AutoComplete::widget([
                'value' => $clientValue,
                'options' => ['id' => 'client_name'],
                'clientOptions' => [
                    'minLength' => '3',
                    'source' => new JsExpression('
                        function (request, response) {
                            $.ajax({
                                url: $("#client_id").data("url"),
                                method: "POST",
                                data: {
                                    workshopId: $("#workshop_id").val(),
                                    term: request.term
                                },
                                dataType: "json",
                                success: function(data) {
                                    response($.map(data.clients, function (rt) {
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
                            $("#client_id").val(ui.item.value);
                            
                            $.ajax({
                                url: $("#client_id").data("select"),
                                method: "GET",
                                data: {
                                    id: ui.item.value
                                },
                                success: function(html) {
                                    $("#client-modal .modal-body").html(html);
                                },
                                error: function (jqXHR, status) {
                                    console.log(status);
                                    response([]);
                                }
                            });
                        }
                    '),
                ]
            ]);

            $attributes['client_id'] .= sprintf('<div class="form-group">%s%s</div>', $autocompleteClient, $btnSaveClient);
        }


        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_manufacturer_id'])) {
            $attributes['client_manufacturer_id'] = $form
                ->field($model,
                    'client_manufacturer_id',
                    [
                        'options' => ['class' => ''],
                        'inputOptions' => [
                            'id' => 'client_manufacturer_id',
                            'data-url' => Url::to(['client/auto-complete']),
                            'data-select' => Url::to(['client/update-modal'])
                        ]
                    ]
                )
                ->hiddenInput();

            $clientValue = $model->client_manufacturer_id ? $model->clientManufacturer->name : '';
            if (\Yii::$app->user->can('updateClient')) {
                $btnSaveClient = Html::a('Редактировать', '#', [
                        'class' => 'btn btn-success client-modal-btn',
                        'disabled' => self::isDisabled($model, 'client_manufacturer_id')
                    ]) .
                    ' ' .
                    Html::a('Новый клиент', '#', [
                        'class' => 'btn btn-success new-client-modal-btn',
                        'disabled' => self::isDisabled($model, 'client_manufacturer_id')
                    ]);
            } else {
                $btnSaveClient = '';
            }

            $autocompleteClient = AutoComplete::widget([
                'value' => $clientValue,
                'options' => ['id' => 'client_manufacturer_name'],
                'clientOptions' => [
                    'minLength' => '3',
                    'source' => new JsExpression('
                        function (request, response) {
                            $.ajax({
                                url: $("#client_manufacturer_id").data("url"),
                                method: "POST",
                                data: {
                                    workshopId: $("#workshop_id").val(),
                                    term: request.term
                                },
                                dataType: "json",
                                success: function(data) {
                                    response($.map(data.clients, function (rt) {
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
                            $("#client_manufacturer_id").val(ui.item.value);
                            
                            $.ajax({
                                url: $("#client_manufacturer_id").data("select"),
                                method: "GET",
                                data: {
                                    id: ui.item.value
                                },
                                success: function(html) {
                                    $("#client-modal .modal-body").html(html);
                                },
                                error: function (jqXHR, status) {
                                    console.log(status);
                                    response([]);
                                }
                            });
                        }
                    '),
                ]
            ]);

            $attributes['client_manufacturer_id'] .= sprintf('<div class="form-group">%s%s</div>', $autocompleteClient, $btnSaveClient);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])) {
            $attributes['treatment_type'] = $form->field($model, 'treatment_type',
                ['labelOptions' => HintHelper::getLabelOptions('treatment_type', $hints)]
            )->dropDownList(Bid::TREATMENT_TYPES, [
                'prompt' => 'Выбор',
                'disabled' => self::isDisabled($model, 'treatment_type')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'saler_name'])) {
            $attributes['saler_name'] = $form->field($model, 'saler_name',
                ['labelOptions' => HintHelper::getLabelOptions('saler_name', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'saler_name')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'purchase_date'])) {
            $attributes['purchase_date'] = $form->field($model, 'purchase_date',
                ['labelOptions' => HintHelper::getLabelOptions('purchase_date', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'purchase_date')
                    ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_manufacturer'])) {
            $attributes['date_manufacturer'] = $form->field($model, 'date_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('date_manufacturer', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'date_manufacturer')
                    ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion'])) {
            $attributes['date_completion'] = $form->field($model, 'date_completion',
                ['labelOptions' => HintHelper::getLabelOptions('date_completion', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'date_completion')
                    ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion_manufacturer'])) {
            $attributes['date_completion_manufacturer'] = $form->field($model, 'date_completion_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('date_completion_manufacturer', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'class' => 'form-control',
                    'disabled' => self::isDisabled($model, 'date_completion_manufacturer')
                ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_number'])) {
            $attributes['warranty_number'] = $form->field($model, 'warranty_number',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_number', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'warranty_number')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_1C_number'])) {
            $attributes['bid_1C_number'] = $form->field($model, 'bid_1C_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_1C_number', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'bid_1C_number')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_manufacturer_number'])) {
            $attributes['bid_manufacturer_number'] = $form->field($model, 'bid_manufacturer_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_manufacturer_number', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'bid_manufacturer_number')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])) {
            $attributes['repair_status_id'] = $form->field($model, 'repair_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('repair_status_id', $hints)]
            )
                ->dropDownList(RepairStatus::repairStatusAsMap(),[
                    'prompt' => 'Выбор',
                    'disabled' => self::isDisabled($model, 'repair_status_id')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_recommendations'])) {
            $attributes['repair_recommendations'] = $form->field($model, 'repair_recommendations',
                ['labelOptions' => HintHelper::getLabelOptions('repair_recommendations', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'repair_recommendations')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'manager'])) {
            $attributes['manager'] = $form->field($model, 'manager_presale',
                ['labelOptions' => HintHelper::getLabelOptions('manager', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'manager')  || (
                        \Yii::$app->user->can('master') && !empty($model->manager)
                    )
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'manager_contact'])) {
            $attributes['manager_contact'] = $form->field($model, 'manager_contact',
                ['labelOptions' => HintHelper::getLabelOptions('manager_contact', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'manager_contact')  || (
                        \Yii::$app->user->can('master') && !empty($model->manager_contact)
                    )
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'manager_presale'])) {
            $attributes['manager_presale'] = $form->field($model, 'manager_presale',
                ['labelOptions' => HintHelper::getLabelOptions('manager_presale', $hints)]
            )->textInput([
                'maxlength' => true,
                'disabled' => self::isDisabled($model, 'manager_presale')  || (
                        \Yii::$app->user->can('master') && !empty($model->manager_presale)
                    )
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])) {
            $attributes['warranty_status_id'] = $form->field($model, 'warranty_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_status_id', $hints)]
            )
                ->dropDownList(WarrantyStatus::warrantyStatusAsMap(),[
                    'prompt' => 'Выбор',
                    'disabled' => self::isDisabled($model, 'warranty_status_id')
                    ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'decision_workshop_status_id'])) {
            $attributes['decision_workshop_status_id'] = $form->field($model, 'decision_workshop_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('decision_workshop_status_id', $hints)]
            )
                ->dropDownList(\app\models\DecisionWorkshopStatus::decisionWorkshopStatusAsMap(), [
                    'prompt' => 'Выбор',
                    'disabled' => self::isDisabled($model, 'decision_workshop_status_id')
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'decision_agency_status_id'])) {
            $attributes['decision_agency_status_id'] = $form->field($model, 'decision_agency_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('decision_agency_status_id', $hints)]
            )
                ->dropDownList(\app\models\DecisionAgencyStatus::decisionAgencyStatusAsMap(),[
                    'prompt' => 'Выбор',
                    'disabled' => self::isDisabled($model, 'decision_agency_status_id')
                    ]);
        }

        if (\Yii::$app->user->can('admin')) {
            $attributes['status_id'] = $form->field($model, 'status_id',
                ['labelOptions' => HintHelper::getLabelOptions('status_id', $hints)]
            )
                ->dropDownList(BidStatus::bidStatusAsMap(),[
                    'prompt' => 'Выбор',
                    'disabled' => self::isDisabled($model, 'status_id')
                ]);
        }

        $attributes['master_id'] = $form->field($model, 'master_id',
            ['labelOptions' => HintHelper::getLabelOptions('master_id', $hints)]
        )
            ->dropDownList(Master::mastersAsMap(\Yii::$app->user->identity),[
                'prompt' => 'Выбор',
                'disabled' => self::isDisabled($model, 'master_id') || (
                    \Yii::$app->user->can('master') && !empty($model->master_id)
                    )
                ]);

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment'])) {
            $attributes['comment'] = $form->field($model, 'comment',
                ['labelOptions' => HintHelper::getLabelOptions('comment', $hints)]
            )->textarea([
                'disabled' => self::isDisabled($model, 'comment')
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment_1'])) {
            $attributes['comment_1'] =  $form->field($model, 'comment_1',
                ['labelOptions' => HintHelper::getLabelOptions('comment_1', $hints)]
            )->textarea([
                'disabled' => self::isDisabled($model, 'comment_1')
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment_2'])) {
            $attributes['comment_2'] =  $form->field($model, 'comment_2',
                ['labelOptions' => HintHelper::getLabelOptions('comment_2', $hints)]
            )->textarea([
                'disabled' => self::isDisabled($model, 'comment_2')
            ]);
        }

        $attributes['workshop_id'] =  $form->field($model, 'workshop_id',
            ['inputOptions' => ['id' => 'workshop_id']])->hiddenInput()->label(false);

        return $attributes;
    }

    public static function getEditSection(Bid $bid, User $user, $sectionName, $isFilledByDefault = true)
    {
        if ($user->can('master')) {
            return $bid->workshop->getSectionsAttributes()->$sectionName;
        } else {
            return $isFilledByDefault ? array_keys($bid->attributeLabels()) : [];
        }
    }

    public static function isDisabled(Bid $bid, $attribute)
    {
        return $bid->is_control
            && !\Yii::$app->user->can('adminBidAttribute', ['attribute' => $attribute, 'is_control' => true]);
    }
}