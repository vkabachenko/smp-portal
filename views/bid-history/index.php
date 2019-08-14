<?php


use yii\grid\GridView;
use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $bidId int */

$this->title = 'История заявки';
$this->params['back'] = ['bid/view', 'id' => $bidId];
?>

<div>
    <h3>История заявки</h3>

    <div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = date('d.m.Y H:i', strtotime($model->created_at));
                        $html = Html::a($text, ['view', 'id' => $model->id] );
                        return $html;
                    },
                ],
                [
                    'attribute' => 'user_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->user_id ? $model->user->username : null, ['class' => 'grid-table']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'action',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\BidHistory */
                        $html = Html::tag('div', $model->action, ['class' => 'grid-table']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
