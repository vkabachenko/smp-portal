<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Agency;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Представительства';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новое представительство', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'header' => 'Производитель',
                'value' => 'manufacturer.name'
            ],
            [
                'header' => 'Менеджеры',
                'format' => 'raw',
                'value' => function (Agency $agency) {
                    return Html::a('Менеджеры', ['agency-manager/all-managers', 'agencyId' => $agency->id]);
                }
            ],
            [
                'header' => 'Мастерские',
                'format' => 'raw',
                'value' => function (Agency $agency) {
                    return Html::a('Мастерские', ['agency-workshop/workshops', 'agencyId' => $agency->id]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
