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
];

if (\Yii::$app->user->can('manageCatalogs')) {
    $columns[] = [
        'attribute' => 'act_template',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model app\models\Manufacturer*/
            $html = Html::a($model->act_template, ['download/act-excel', 'filename' => $model->act_template]);
            return $html;
        },
    ];
    $columns[] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}{delete}',
    ];
}
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\Yii::$app->user->can('manageCatalogs')): ?>
        <p>
            <?= Html::a('Новый производитель', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]); ?>


</div>
