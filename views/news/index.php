<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'created_at',
            'title',
            [
                'attribute' => 'target',
                'value' => function ($model) {
                    /* @var $model app\models\News */
                    $html = \app\models\News::TARGETS[$model->target];
                    return $html;
                }
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\News */
                    $html = $model->active ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
