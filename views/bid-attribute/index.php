<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Bid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поля заявки';
$this->params['back'] = ['admin/index'];
?>
<div class="bid-attribute-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить поле', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'attribute',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    return Bid::EDITABLE_ATTRIBUTES[$model->attribute];
                },
            ],
            'short_description',
            [
                'attribute' => 'is_disabled_agencies',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    $html = $model->is_disabled_agencies ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                }
            ],
            [
                'attribute' => 'is_disabled_workshops',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    $html = $model->is_disabled_workshops ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                }
            ],
            [
                'attribute' => 'is_control',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    $html = $model->is_control ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
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
