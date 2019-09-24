<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Менеджеры';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый менеджер', ['create'], ['class' => 'btn btn-success', 'disabled' => true]) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    /* @var $model app\models\Manager */
                    return $model->user->name;
                },
            ],
            [
                'attribute' => 'manufacturer_id',
                'value' => function ($model) {
                    /* @var $model app\models\Manager */
                    return $model->manufacturer->name;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>


</div>
