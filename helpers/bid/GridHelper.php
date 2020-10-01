<?php


namespace app\helpers\bid;

use app\helpers\constants\Constants;
use app\models\Client;
use yii\helpers\Json;
use app\models\Manufacturer;
use app\models\RepairStatus;
use app\models\search\BidSearch;
use app\models\WarrantyStatus;
use yii\bootstrap\Html;
use app\models\BidCommentsRead;
use app\models\Bid;
use app\models\Master;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;


class GridHelper
{
    /* @var array */
    private $gridAttributes;

    /* @var BidSearch */
    private $searchModel;

    public function __construct($gridAttributes, $searchModel)
    {
        $this->gridAttributes = $gridAttributes;
        $this->searchModel = $searchModel;
    }

    public function getCss()
    {
        $templateHide = '@media(%s) {
                    .bids-grid .%s {
                        display: none;
                    }
                }';
        $templateShow = '@media(%s) {
                    .bids-grid .%s {
                        display: table-cell;
                    }
                }';
        $desktop = 'min-width:768px';
        $tablet = 'max-width:767px';
        $phone  = 'max-width:450px';

        $css = '';
        foreach ($this->gridAttributes as $attribute => $params)
        {
            $class = 'grid-' . $attribute;
            if (!$params['desktop']) {
                $css .= sprintf($templateHide, $desktop, $class);
            } else {
                $css .= sprintf($templateShow, $desktop, $class);
            }
            if (!$params['tablet']) {
                $css .= sprintf($templateHide, $tablet, $class);
            } else {
                $css .= sprintf($templateShow, $tablet, $class);
            }
            if (!$params['phone']) {
                $css .= sprintf($templateHide, $phone, $class);
            } else {
                $css .= sprintf($templateShow, $phone, $class);
            }
        }
        return $css;
    }

    public function getColumns()
    {
        $attributeMethods = [
            'equipment' => 'getEquipmentColumn',
            'equipment_manufacturer' => 'getEquipmentManufacturerColumn',
            'bid_1C_number'  => 'getBid1CNumberColumn',
            'bid_number'  => 'getBidNumberColumn',
            'client_id'  => 'getClientColumn',
            'client_manufacturer_id'  => 'getClientManufacturerColumn',
            'master_id'  => 'getMasterColumn',
            'condition_name'  => 'getConditionColumn',
            'condition_manufacturer_name'  => 'getConditionManufacturerColumn',
            'brand_name'  => 'getBrandColumn',
            'brand_model_name'  => 'getBrandModelColumn',
            'composition_name'  => 'getCompositionColumn',
            'composition_name_manufacturer'  => 'getCompositionManufacturerColumn',
            'created_at'  => 'getCreatedAtColumn',
            'status_id'  => 'getStatusColumn',
            'repair_status_id'  => 'getRepairStatusColumn',
            'treatment_type'  => 'getTreatmentTypeColumn',
            'warranty_status_id'  => 'getWarrantyStatusColumn',
            'manufacturer_id'  => 'getManufacturerColumn'
        ];


        $columns = [];
        $columns[0] = $this->getCheckNewCommentColumn();

        foreach ($this->gridAttributes as $attribute => $params) {
            $columnMethodName = isset($attributeMethods[$attribute]) ? $attributeMethods[$attribute] : null;
            if ($columnMethodName) {
                $columns[$this->getColumnNumber($attribute)] = $this->{$columnMethodName}();
            }
        }
        return $columns;
    }

    private function getCheckNewCommentColumn()
    {
        return [
            'format' => 'raw',
            'header' => '&nbsp;',
            'value' => function ($model) {
                return BidCommentsRead::isExistUnread($model->id)
                    ? Html::a('<span class="glyphicon glyphicon-asterisk" aria-hidden="true" title="Имеются непрочитанные комментарии"></span>',
                        ['bid-comment/index', 'bidId' => $model->id])
                    : '';
            },
            'filterOptions' => ['class' => 'grid-comment-read'],
            'headerOptions' => ['class' => 'grid-comment-read'],
            'contentOptions' => ['class' => 'grid-comment-read'],
        ];
    }

    private function getColumnNumber($attribute)
    {
        return 1 + intval(array_search($attribute, array_keys($this->gridAttributes)));
    }

    private function getEquipmentColumn()
    {
        return [
            'attribute' => 'equipment',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $name = $model->equipment ?: 'Оборудование не задано';
                $a = Html::a($name, ['view', 'id' => $model->id]);
                $html = Html::tag('div', $a);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-equipment'],
            'headerOptions' => ['class' => 'grid-equipment'],
            'contentOptions' => ['class' => 'grid-equipment']
        ];
    }

    private function getEquipmentManufacturerColumn()
    {
        return [
            'attribute' => 'equipment_manufacturer',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $name = $model->equipment_manufacturer ?: 'Оборудование не задано';
                $a = Html::a($name, ['view', 'id' => $model->id]);
                $html = Html::tag('div', $a);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-equipment_manufacturer'],
            'headerOptions' => ['class' => 'grid-equipment_manufacturer'],
            'contentOptions' => ['class' => 'grid-equipment_manufacturer']
        ];
    }

    private function getBid1CNumberColumn()
    {
        return [
            'attribute' => 'bid_1C_number',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = Html::tag('div', $model->bid_1C_number);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-bid_1C_number'],
            'headerOptions' => ['class' => 'grid-bid_1C_number'],
            'contentOptions' => ['class' => 'grid-bid_1C_number']
        ];
    }

    private function getBidNumberColumn()
    {
        return [
            'attribute' => 'bid_number',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = Html::tag('div', $model->bid_number);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-bid_number'],
            'headerOptions' => ['class' => 'grid-bid_number'],
            'contentOptions' => ['class' => 'grid-bid_number']
        ];
    }

    private function getClientColumn()
    {
        $workshopId = \Yii::$app->user->identity->master
            ? \Yii::$app->user->identity->master->workshop_id
            : null;
        $url = Url::to(['client/auto-complete']);
        return [
            'attribute' => 'client_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->client_id
                    ? Html::tag('div', $model->client->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-client_id'],
            'headerOptions' => ['class' => 'grid-client_id'],
            'contentOptions' => ['class' => 'grid-client_id'],
            'filter' => AutoComplete::widget([
                'name' => 'BidSearch[client_name]',
                'value' => $this->searchModel->client_name,
                'clientOptions' => [
                    'minLength' => '3',
                    'source' => new JsExpression('
                        function (request, response) {
                            console.log("request");
                            $.ajax({
                                url: ' . Json::htmlEncode($url) . ',
                                method: "POST",                              
                                data: {
                                    workshopId: ' . Json::htmlEncode($workshopId) . ',
                                    term: request.term
                                },
                                dataType: "json",
                                success: function(data) {
                                    response($.map(data.clients, function (rt) {
                                        return {
                                            label: rt.name,
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
                        }
                    ')
                ]
        ])
    ];
    }

    private function getClientManufacturerColumn()
    {
        $workshopId = \Yii::$app->user->identity->master
            ? \Yii::$app->user->identity->master->workshop_id
            : null;
        $url = Url::to(['client/auto-complete']);

        return [
            'attribute' => 'client_manufacturer_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->client_manufacturer_id
                    ? Html::tag('div', $model->clientManufacturer->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-client_manufacturer_id'],
            'headerOptions' => ['class' => 'grid-client_manufacturer_id'],
            'contentOptions' => ['class' => 'grid-client_manufacturer_id'],
            'filter' => AutoComplete::widget([
                'name' => 'BidSearch[client_manufacturer_name]',
                'value' => $this->searchModel->client_manufacturer_name,
                'clientOptions' => [
                    'minLength' => '3',
                    'source' => new JsExpression('
                        function (request, response) {
                            console.log("request");
                            $.ajax({
                                url: ' . Json::htmlEncode($url) . ',
                                method: "POST",                              
                                data: {
                                    workshopId: ' . Json::htmlEncode($workshopId) . ',
                                    term: request.term
                                },
                                dataType: "json",
                                success: function(data) {
                                    response($.map(data.clients, function (rt) {
                                        return {
                                            label: rt.name,
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
                        }
                    ')
                ]
            ])
        ];
    }

    private function getMasterColumn()
    {
        return [
            'attribute' => 'master_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->master_id
                    ? Html::tag('div', $model->master->user->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-master_id'],
            'headerOptions' => ['class' => 'grid-master_id'],
            'contentOptions' => ['class' => 'grid-master_id'],
            'filter' => Master::mastersAsMap(\Yii::$app->user->identity, true)
        ];
    }

    private function getConditionColumn()
    {
        return [
            'attribute' => 'condition_name',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->condition_name
                    ? Html::tag('div', $model->condition_name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-condition_name'],
            'headerOptions' => ['class' => 'grid-condition_name'],
            'contentOptions' => ['class' => 'grid-condition_name'],
        ];
    }

    private function getConditionManufacturerColumn()
    {
        return [
            'attribute' => 'condition_manufacturer_name',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->condition_manufacturer_name
                    ? Html::tag('div', $model->condition_manufacturer_name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-condition_manufacturer_name'],
            'headerOptions' => ['class' => 'grid-condition_manufacturer_name'],
            'contentOptions' => ['class' => 'grid-condition_manufacturer_name'],
        ];
    }

    private function getBrandColumn()
    {
        return [
            'attribute' => 'brand_name',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html =  Html::tag('div', $model->brand_name);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-brand_name'],
            'headerOptions' => ['class' => 'grid-brand_name'],
            'contentOptions' => ['class' => 'grid-brand_name'],
        ];
    }

    private function getBrandModelColumn()
    {
        return [
            'attribute' => 'brand_model_name',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = Html::tag('div', $model->brand_model_name);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-brand_model_name'],
            'headerOptions' => ['class' => 'grid-brand_model_name'],
            'contentOptions' => ['class' => 'grid-brand_model_name'],
        ];
    }

    private function getCompositionColumn()
    {
        return [
            'attribute' => 'composition_name',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = Html::tag('div', $model->composition_name);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-composition_name'],
            'headerOptions' => ['class' => 'grid-composition_name'],
            'contentOptions' => ['class' => 'grid-composition_name'],
        ];
    }

    private function getCompositionManufacturerColumn()
    {
        return [
            'attribute' => 'composition_name_manufacturer',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = Html::tag('div', $model->composition_name_manufacturer);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-composition_name_manufacturer'],
            'headerOptions' => ['class' => 'grid-composition_name_manufacturer'],
            'contentOptions' => ['class' => 'grid-composition_name_manufacturer'],
        ];
    }

    private function getCreatedAtColumn()
    {
        return [
            'attribute' => 'created_at',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $text = date('d.m.Y H:i', strtotime($model->created_at));
                $html = Html::tag('div', $text);
                return $html;
            },
            'filterOptions' => ['class' => 'grid-created_at'],
            'headerOptions' => ['class' => 'grid-created_at'],
            'contentOptions' => ['class' => 'grid-created_at'],
            'filter' => DatePicker::widget([
                'model' => $this->searchModel,
                'attribute' => 'created_at_from',
                'attribute2' => 'created_at_to',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
            ])
        ];
    }

    private function getStatusColumn()
    {
        return [
            'attribute' => 'status_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->status_id
                    ? Html::tag('div', $model->status->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-status_id'],
            'headerOptions' => ['class' => 'grid-status_id'],
            'contentOptions' => ['class' => 'grid-status_id'],
            'filter' => \app\models\BidStatus::bidStatusAsMap(true)
        ];
    }

    private function getRepairStatusColumn()
    {
        return [
            'attribute' => 'repair_status_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->repair_status_id
                    ? Html::tag('div', $model->repairStatus->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-repair_status_id'],
            'headerOptions' => ['class' => 'grid-repair_status_id'],
            'contentOptions' => ['class' => 'grid-repair_status_id'],
            'filter' => RepairStatus::repairStatusAsMap(true)
        ];
    }

    private function getTreatmentTypeColumn()
    {
        return [
            'attribute' => 'treatment_type',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->treatment_type
                    ? Html::tag('div', Bid::TREATMENT_TYPES[$model->treatment_type])
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-treatment_type'],
            'contentOptions' => ['class' => 'grid-treatment_type'],
            'headerOptions' => ['class' => 'grid-treatment_type'],
            'filter' => Constants::EMPTY_ELEMENT + Bid::TREATMENT_TYPES
        ];
    }

    private function getWarrantyStatusColumn()
    {
        return [
            'attribute' => 'warranty_status_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->warranty_status_id
                    ? Html::tag('div', $model->warrantyStatus->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-warranty_status_id'],
            'headerOptions' => ['class' => 'grid-warranty_status_id'],
            'contentOptions' => ['class' => 'grid-warranty_status_id'],
            'filter' => WarrantyStatus::warrantyStatusAsMap()
        ];
    }

    private function getManufacturerColumn()
    {
        return [
            'attribute' => 'manufacturer_id',
            'format' => 'raw',
            'value' => function (Bid $model) {
                $html = $model->manufacturer_id
                    ? Html::tag('div', $model->manufacturer->name)
                    : null;
                return $html;
            },
            'filterOptions' => ['class' => 'grid-manufacturer_id'],
            'headerOptions' => ['class' => 'grid-manufacturer_id'],
            'contentOptions' => ['class' => 'grid-manufacturer_id'],
            'filter' => Manufacturer::manufacturersAsMap(true)
        ];
    }

}