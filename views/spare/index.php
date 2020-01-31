<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $bidId int */

$this->title = 'Запчасти';
$this->params['back'] = ['bid/view', 'id' => $bidId];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\Yii::$app->user->can('manageSpare', ['bidId' => $bidId])): ?>
    <p>
        <?= Html::a('Новая запчасть', ['create', 'bidId' => $bidId], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'quantity',
            'price',
            'total_price',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => \Yii::$app->user->can('manageSpare', ['bidId' => $bidId])
                    ? '{view}{update}{delete}'
                    : '{view}',
            ],
        ],
    ]); ?>


</div>
