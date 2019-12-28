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
                'attribute' => 'is_enabled_agencies',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    return $model->asText('is_enabled_agencies');
                },
            ],
            [
                'attribute' => 'is_enabled_workshops',
                'value' => function ($model) {
                    /* @var $model app\models\BidAttribute */
                    return $model->asText('is_enabled_workshops');
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
