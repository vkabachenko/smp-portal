<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Таблица соответствия брендов';
$this->params['back'] = ['admin/index'];
?>
<div class="brand-correspondence-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новое соответствие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'brand_id',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
