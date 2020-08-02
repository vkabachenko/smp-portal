<?php


namespace app\helpers\bid;


use app\models\Bid;
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

class EditHelper
{
    public static function getAttributesEdit(Bid $model, User $user, ActiveForm $form)
    {
        $attributes = [];

        $attributes['brand_id'] = $form->field($model, 'brand_id')->hiddenInput([
            'id' => 'bid-brand-id',
            'data-url' => Url::to(['bid/brand'])
        ])
            ->label(false);

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
                    'id' => 'bid-brand-name'
                ]
            ]);

        $attributes['manufacturer_id'] = $form->field($model, 'manufacturer_id')
            ->dropDownList(Manufacturer::manufacturersAsMap(),[
                'prompt' => 'Выбор',
                'id' => 'bid-manufacturer-id',
            ]);

        $attributes['equipment'] = $form->field($model, 'equipment')->textInput(['maxlength' => true]);

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
                        'id' => 'bid-brand-model-name'
                    ]
                ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'serial_number'])) {
            $attributes['serial_number'] = $form->field($model, 'serial_number',
                ['labelOptions' => HintHelper::getLabelOptions('serial_number', $hints)]
            )->textInput([
                'id' => 'bid-serial-number',
                'maxlength' => true
            ]);
            $attributes['serial_number'] .= <<<HTML
        <div class="form-group">
            <span style="font-weight: bold;">Файл со штрих-кодом серийного номера</span>
            <?= Html::fileInput('', null, ['id' => 'bid-serial-number-file']) ?>
        </div>
HTML;
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])) {
            $attributes['vendor_code'] = $form->field($model, 'vendor_code',
                ['labelOptions' => HintHelper::getLabelOptions('vendor_code', $hints)]
            )->textInput(['maxlength' => true]);
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
                    'id' => 'bid-composition-name'
                ]
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'author'])) {
            $attributes['author'] = $form->field($model, 'author',
                ['labelOptions' => HintHelper::getLabelOptions('author', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (!\Yii::$app->user->can('master')
            && \Yii::$app->user->can('adminBidAttribute', ['attribute' => 'sum_manufacturer'])) {
            $attributes['sum_manufacturer'] = $form->field($model, 'sum_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('sum_manufacturer', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'subdivision'])) {
            $attributes['subdivision'] = $form->field($model, 'subdivision',
                ['labelOptions' => HintHelper::getLabelOptions('subdivision', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect'])) {
            $attributes['defect'] = $form->field($model, 'defect',
                ['labelOptions' => HintHelper::getLabelOptions('defect', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (!\Yii::$app->user->can('master')
            && \Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect_manufacturer'])) {
            $attributes['defect_manufacturer'] = $form->field($model, 'defect_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('defect_manufacturer', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_control'])) {
            $attributes['is_control'] = $form->field($model, 'is_control')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_control', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_report'])) {
            $attributes['is_report'] = $form->field($model, 'is_report')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_report', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_reappeal'])) {
            $attributes['is_reappeal'] = $form->field($model, 'is_reappeal')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_reappeal', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'document_reappeal'])) {
            $attributes['document_reappeal'] = $form->field($model, 'document_reappeal',
                ['labelOptions' => HintHelper::getLabelOptions('document_reappeal', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty'])) {
            $attributes['is_warranty'] = $form->field($model, 'is_warranty')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_warranty', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty_defect'])) {
            $attributes['is_warranty_defect'] = $form->field($model, 'is_warranty_defect')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_warranty_defect', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_comment'])) {
            $attributes['warranty_comment'] = $form->field($model, 'warranty_comment',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_comment', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_repair_possible'])) {
            $attributes['is_repair_possible'] = $form->field($model, 'is_repair_possible')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_repair_possible', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_for_warranty'])) {
            $attributes['is_for_warranty'] = $form->field($model, 'is_for_warranty')
                ->checkbox(['labelOptions' => HintHelper::getLabelOptions('is_for_warranty', $hints)]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic'])) {
            $attributes['diagnostic'] = $form->field($model, 'diagnostic',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic', $hints)]
            )->textarea();
        }

        if (!\Yii::$app->user->can('master') &&
            \Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic_manufacturer'])) {
            $attributes['diagnostic_manufacturer'] = $form->field($model, 'diagnostic_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('diagnostic_manufacturer', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_id'])) {
            $attributes['condition_id'] = $form->field($model, 'condition_id',
                ['labelOptions' => HintHelper::getLabelOptions('condition_id', $hints)]
            )
                ->dropDownList(Condition::conditionsAsMap(),['prompt' => 'Выбор', 'class' => 'form-control bid-condition']);
        }

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

        $attributes['client_id'] .= <<<HTML
    <div class="form-group">
        <?= AutoComplete::widget([
            'value' => $model->client_id ? $model->client->name : '',
            'options' => ['id' => 'client_name'],
            'clientOptions' => [
                'minLength'=>'3',
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
        ]) ?>

        <?php if (\Yii::$app->user->can('updateClient')): ?>
            <?= Html::a('Редактировать', '#', ['class' => 'btn btn-success client-modal-btn']) ?>
            <?= Html::a('Новый клиент', '#', ['class' => 'btn btn-success new-client-modal-btn']) ?>
        <?php endif; ?>

    </div>
HTML;

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])) {
            $attributes['treatment_type'] = $form->field($model, 'treatment_type',
                ['labelOptions' => HintHelper::getLabelOptions('treatment_type', $hints)]
            )->dropDownList(Bid::TREATMENT_TYPES, ['prompt' => 'Выбор']);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'saler_name'])) {
            $attributes['saler_name'] = $form->field($model, 'saler_name',
                ['labelOptions' => HintHelper::getLabelOptions('saler_name', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'purchase_date'])) {
            $attributes['purchase_date'] = $form->field($model, 'purchase_date',
                ['labelOptions' => HintHelper::getLabelOptions('purchase_date', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => ['class' => 'form-control']
            ]);
        }

        if (!\Yii::$app->user->can('master')
            && \Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_manufacturer'])) {
            $attributes['date_manufacturer'] = $form->field($model, 'date_manufacturer',
                ['labelOptions' => HintHelper::getLabelOptions('date_manufacturer', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => ['class' => 'form-control']
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion'])) {
            $attributes['date_completion'] = $form->field($model, 'date_completion',
                ['labelOptions' => HintHelper::getLabelOptions('date_completion', $hints)]
            )->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => ['class' => 'form-control']
            ]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_number'])) {
            $attributes['warranty_number'] = $form->field($model, 'warranty_number',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_number', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_1C_number'])) {
            $attributes['bid_1C_number'] = $form->field($model, 'bid_1C_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_1C_number', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (!\Yii::$app->user->can('master') &&
            \Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_manufacturer_number'])) {
            $attributes['bid_manufacturer_number'] = $form->field($model, 'bid_manufacturer_number',
                ['labelOptions' => HintHelper::getLabelOptions('bid_manufacturer_number', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])) {
            $attributes['repair_status_id'] = $form->field($model, 'repair_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('repair_status_id', $hints)]
            )
                ->dropDownList(RepairStatus::repairStatusAsMap(),['prompt' => 'Выбор']);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_recommendations'])) {
            $attributes['repair_recommendations'] = $$form->field($model, 'repair_recommendations',
                ['labelOptions' => HintHelper::getLabelOptions('repair_recommendations', $hints)]
            )->textInput(['maxlength' => true]);
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])) {
            $attributes['warranty_status_id'] = $form->field($model, 'warranty_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('warranty_status_id', $hints)]
            )
                ->dropDownList(WarrantyStatus::warrantyStatusAsMap(),['prompt' => 'Выбор']);
        }

        $attributes['decision_workshop_status_id'] = $form->field($model, 'decision_workshop_status_id',
            ['labelOptions' => HintHelper::getLabelOptions('decision_workshop_status_id', $hints)]
        )
            ->dropDownList(\app\models\DecisionWorkshopStatus::decisionWorkshopStatusAsMap(),['prompt' => 'Выбор']);

        if (\Yii::$app->user->can('admin')) {
            $attributes['decision_agency_status_id'] = $form->field($model, 'decision_agency_status_id',
                ['labelOptions' => HintHelper::getLabelOptions('decision_agency_status_id', $hints)]
            )
                ->dropDownList(\app\models\DecisionAgencyStatus::decisionAgencyStatusAsMap(),['prompt' => 'Выбор']);
        }

        $attributes['master_id'] = $form->field($model, 'master_id',
            ['labelOptions' => HintHelper::getLabelOptions('master_id', $hints)]
        )
            ->dropDownList(Master::mastersAsMap(\Yii::$app->user->identity),['prompt' => 'Выбор']);

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment'])) {
            $attributes['comment'] = $form->field($model, 'comment',
                ['labelOptions' => HintHelper::getLabelOptions('comment', $hints)]
            )->textarea();
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment_1'])) {
            $attributes['comment_1'] =  $form->field($model, 'comment_1',
                ['labelOptions' => HintHelper::getLabelOptions('comment_1', $hints)]
            )->textarea();
        }

        if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment_2'])) {
            $attributes['comment_2'] =  $form->field($model, 'comment_2',
                ['labelOptions' => HintHelper::getLabelOptions('comment_2', $hints)]
            )->textarea();
        }

        $attributes['workshop_id'] =  $form->field($model, 'workshop_id',
            ['inputOptions' => ['id' => 'workshop_id']])->hiddenInput()->label(false);
    }
}