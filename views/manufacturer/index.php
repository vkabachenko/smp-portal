<?php


use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Производители';
$this->params['back'] = ['admin/catalogs'];

$columns = [
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Manufacturer*/
            $html = Html::a($model->name, ['brand/index', 'manufacturerId' => $model->id]);
            return $html;
        },
    ],
     [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}{delete}',
    ]
];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Новый производитель', ['create'], ['class' => 'btn btn-success']) ?>
        </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]); ?>


</div>
