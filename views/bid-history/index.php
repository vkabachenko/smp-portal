<?php


use yii\grid\GridView;
use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $bidId int */

$this->title = 'История заявки';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['master/index']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр  заявки', 'url' => ['bid/view', 'id' => $bidId]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <h3>История заявки</h3>

    <div>
        <p>
            <?= Html::a('Новый этап', ['create', 'bidId' => $bidId], ['class' => 'btn btn-success']) ?>
        </p>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = date('d.m.Y h:i', strtotime($model->created_at));
                        $html = Html::a($text, ['view', 'id' => $model->id] );
                        return $html;
                    },
                ],
                [
                    'attribute' => 'user_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->user->username, ['class' => 'grid-table']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\BidHistory */
                        $text = \app\models\BidHistory::STATUSES[$model->status];
                        $html = Html::tag('div', $text, ['class' => 'grid-table']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'comment',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\BidHistory */
                        $text = $model->comment;
                        $html = Html::tag('div', $text, ['class' => 'grid-table grid-25']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
