<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $mastersDataProvider yii\data\ActiveDataProvider */
/* @var $workshop \app\models\Workshop */

$this->title = 'Мастера мастерской ' . $workshop->name;
$this->params['back'] = ['workshop/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $mastersDataProvider,
        'columns' => [
            'user.name',
            [
                'attribute' => 'main',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Master */
                    $html = $model->main ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                },
            ],
            'phone',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', $url. '&workshopId=' . $model->workshop_id, ['class' => 'glyphicon glyphicon-pencil']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', $url. '&workshopId=' . $model->workshop_id,
                            ['class' => 'glyphicon glyphicon-trash', 'data' => ['method' => 'post']]);
                    }
                ],
            ],
        ],
    ]); ?>

</div>