<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мастера';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый мастер', ['create'], ['class' => 'btn btn-success', 'disabled' => true]) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    /* @var $model app\models\Master */
                    return $model->user->name;
                },
            ],
            [
                'attribute' => 'workshop_id',
                'value' => function ($model) {
                    /* @var $model app\models\Master */
                    return $model->workshop->name;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>


</div>
