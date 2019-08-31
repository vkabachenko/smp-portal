<?php


use app\models\Bid;
use app\models\Manufacturer;
use app\models\RepairStatus;
use app\models\User;
use app\models\WarrantyStatus;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $title string */

$this->title = $title;
?>

<div>
    <h2> <?= $this->title . ' ' . \Yii::$app->user->identity->name ?></h2>

    <div class="bids-grid">
        <div>
            <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="bid-search-btn-wrap">
            <?= Html::button('Расширенный поиск', [
                'class' => 'btn btn-primary',
                'onclick' => '$(".bid-search").show();$(".bid-search-text").hide();'
            ])
            ?>
            <?= $this->render('_search-text', ['model' => $searchModel]); ?>
        </div>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = date('d.m.Y H:i', strtotime($model->created_at));
                        $a = Html::a($text, ['view', 'id' => $model->id]);
                        $html = Html::tag('div', $a);
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
                [
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
                ],
                [
                    'attribute' => 'bid_1C_number',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->bid_1C_number);
                        return $html;
                    },
                    'filterOptions' => ['class' => 'grid-bid-1C-number'],
                    'headerOptions' => ['class' => 'grid-bid-1C-number'],
                    'contentOptions' => ['class' => 'grid-bid-1C-number']
                ],
                [
                    'attribute' => 'equipment',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->equipment);
                        return $html;
                    },
                    'filterOptions' => ['class' => 'grid-equipmant'],
                    'headerOptions' => ['class' => 'grid-equipmant'],
                    'contentOptions' => ['class' => 'grid-equipmant']
                ],
                [
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
                ],
                [
                    'attribute' => 'treatment_type',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = $model->treatment_type
                            ? Html::tag('div', Bid::TREATMENT_TYPES[$model->treatment_type])
                            : null;                        ;
                        return $html;
                    },
                    'filterOptions' => ['class' => 'grid-treatment-type'],
                    'contentOptions' => ['class' => 'grid-treatment-type'],
                    'headerOptions' => ['class' => 'grid-treatment-type'],
                    'filter' => Bid::TREATMENT_TYPES
                ],
                [
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
                ],
                [
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
                ],
                [
                    'attribute' => 'user_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = $model->user_id
                            ? Html::tag('div', $model->user->name)
                            : null;
                        return $html;
                    },
                    'filterOptions' => ['class' => 'grid-master'],
                    'headerOptions' => ['class' => 'grid-master'],
                    'contentOptions' => ['class' => 'grid-master'],
                    'filter' => User::mastersAsMap()
                ],
                [
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
                ],
            ],
        ]); ?>
    </div>
</div>


