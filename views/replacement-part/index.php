<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $bidId int */

$this->title = 'Артикулы для сервиса';
$this->params['back'] = Url::to(['bid/view', 'id' => $bidId]);
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый артикул', ['create', 'bidId' => $bidId], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            'name',
            'price',
            'quantity',
            'total_price',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>',
                            [
                                'replacement-part/view',
                                'id' => $model->id,
                                'returnUrl' => Url::to(['replacement-part/index', 'bidId' => $model->bid_id])
                            ]);
                    }
                ],
            ],
        ],
    ]); ?>


</div>

