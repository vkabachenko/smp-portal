<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мастерские';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'header' => 'Мастера',
                'format' => 'raw',
                'value' => function (Workshop $workshop) {
                    return Html::a('Мастера', ['workshop-master/all-masters', 'workshopId' => $workshop->id]);
                }
            ],
            [
                'header' => 'Представительства',
                'format' => 'raw',
                'value' => function (Workshop $workshop) {
                    return Html::a('Представительства', ['workshop-agency/agencies', 'workshopId' => $workshop->id]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
