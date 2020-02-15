<?php


use app\models\Bid;
use app\models\Manufacturer;
use app\models\Master;
use app\models\RepairStatus;
use app\models\WarrantyStatus;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\bootstrap\Html;
use app\helpers\bid\RowOptionsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['back'] = \yii\helpers\Url::previous();

$columns = [
    [
        'attribute' => 'created_at',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $text = date('d.m.Y H:i', strtotime($model->created_at));
            $html = Html::tag('div', $text);
            return $html;
        },
        'filterOptions' => ['class' => 'grid-created-at'],
        'headerOptions' => ['class' => 'grid-created-at'],
        'contentOptions' => ['class' => 'grid-created-at'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'created_at_from',
            'attribute2' => 'created_at_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ])
    ],
];

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_number'])) {
    $columns[] = [
        'attribute' => 'bid_number',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = Html::tag('div', $model->bid_number);
            return $html;
        },
        'filterOptions' => ['class' => 'grid-bid-number'],
        'headerOptions' => ['class' => 'grid-bid-number'],
        'contentOptions' => ['class' => 'grid-bid-number']
    ];
}

$columns[] = [
    'attribute' => 'status_id',
    'format' => 'raw',
    'value' => function ($model) {
        /* @var $model app\models\Bid */
        $html = $model->status_id
            ? Html::tag('div', $model->status->name)
            : null;
        return $html;
    },
    'filterOptions' => ['class' => 'grid-status'],
    'headerOptions' => ['class' => 'grid-status'],
    'contentOptions' => ['class' => 'grid-status'],
    'filter' => \app\models\BidStatus::bidStatusAsMap()
];

$columns[] =  [
    'attribute' => 'equipment',
    'format' => 'raw',
    'value' => function ($model) {
        /* @var $model app\models\Bid */
        $a = Html::a($model->equipment, ['view', 'id' => $model->id]);
        $html = Html::tag('div', $a);
        return $html;
    },
    'filterOptions' => ['class' => 'grid-equipmant'],
    'headerOptions' => ['class' => 'grid-equipmant'],
    'contentOptions' => ['class' => 'grid-equipmant']
];

$columns[] = [
        'attribute' => 'manufacturer_id',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = $model->manufacturer_id
                ? Html::tag('div', $model->manufacturer->name)
                : null;                        ;
            return $html;
        },
        'filterOptions' => ['class' => 'grid-manufacturer'],
        'headerOptions' => ['class' => 'grid-manufacturer'],
        'contentOptions' => ['class' => 'grid-manufacturer'],
        'filter' => Manufacturer::manufacturersAsMap()
];

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])) {
    $columns[] =      [
        'attribute' => 'treatment_type',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = $model->treatment_type
                ? Html::tag('div', Bid::TREATMENT_TYPES[$model->treatment_type])
                : null;
            return $html;
        },
        'filterOptions' => ['class' => 'grid-treatment-type'],
        'contentOptions' => ['class' => 'grid-treatment-type'],
        'headerOptions' => ['class' => 'grid-treatment-type'],
        'filter' => Bid::TREATMENT_TYPES
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])) {
    $columns[] =  [
        'attribute' => 'repair_status_id',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = $model->repair_status_id
                ? Html::tag('div', $model->repairStatus->name)
                : null;                        ;
            return $html;
        },
        'filterOptions' => ['class' => 'grid-repair-status'],
        'headerOptions' => ['class' => 'grid-repair-status'],
        'contentOptions' => ['class' => 'grid-repair-status'],
        'filter' => RepairStatus::repairStatusAsMap()
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])) {
    $columns[] =      [
        'attribute' => 'warranty_status_id',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = $model->warranty_status_id
                ? Html::tag('div', $model->warrantyStatus->name)
                : null;
            return $html;
        },
        'filterOptions' => ['class' => 'grid-warranty-status'],
        'headerOptions' => ['class' => 'grid-warranty-status'],
        'contentOptions' => ['class' => 'grid-warranty-status'],
        'filter' => WarrantyStatus::warrantyStatusAsMap()
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'master_id'])) {
    $columns[] =   [
        'attribute' => 'master_id',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = $model->master_id
                ? Html::tag('div', $model->master->user->name)
                : null;
            return $html;
        },
        'filterOptions' => ['class' => 'grid-master'],
        'headerOptions' => ['class' => 'grid-master'],
        'contentOptions' => ['class' => 'grid-master'],
        'filter' => Master::mastersAsMap(\Yii::$app->user->identity)
    ];
}

$columns[] = [
        'attribute' => 'client_name',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Bid */
            $html = Html::tag('div', $model->client_name);
            return $html;
        },
        'filterOptions' => ['class' => 'grid-client'],
        'headerOptions' => ['class' => 'grid-client'],
        'contentOptions' => ['class' => 'grid-client']
    ];

?>

<div>
    <h2> <?= $this->title ?></h2>

    <div class="bids-grid">
        <div>
            <?php if (\Yii::$app->user->can('createBid')): ?>
                <div class="col-xs-6">
                    <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            <?php endif; ?>

            <div class="bid-search-btn-wrap col-xs-6">
                <?= Html::button('Расширенный поиск', [
                    'class' => 'btn btn-primary',
                    'onclick' => '$(".bid-search").show();$(".bid-search-text").hide();'
                ])
                ?>
                <?= $this->render('_search-text', ['model' => $searchModel]); ?>
            </div>
        </div>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns,
            'rowOptions' => function(Bid $bid) {
                return ['class' => RowOptionsHelper::getClass($bid, \Yii::$app->user->identity->role)];
            }
        ]); ?>
    </div>
</div>


