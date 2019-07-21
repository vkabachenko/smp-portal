<?php


use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Производители';

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Manufacturer*/
                    $html = Html::a($model->name, ['update-template', 'id' => $model->id]);
                    return $html;
                },
            ],
            [
                'attribute' => 'act_template',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Manufacturer*/
                    $html = Html::a($model->act_template, ['download/act-excel', 'filename' => $model->act_template]);
                    return $html;
                },
            ]
        ]
    ]); ?>


</div>
