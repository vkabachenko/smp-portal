<?php


use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $title string */

$this->title = $title;
?>

<div>
    <h2> <?= $this->title . ' ' . \Yii::$app->user->identity->username ?></h2>

    <div>
        <p>
            <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = date('d.m.Y H:i', strtotime($model->created_at));
                        $html = Html::a($text, ['view', 'id' => $model->id]);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'status_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->status_id ? $model->status->name : '', ['class' => 'grid-table']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'manufacturer_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->manufacturer->name, ['class' => 'grid-table grid-20']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'equipment',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->equipment, ['class' => 'grid-table grid-20']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'brand_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = $model->brand_id ? $model->brand->name : '';
                        $html = Html::tag('div', $text, ['class' => 'grid-table grid-20']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'brand_model_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = $model->brand_model_id ? $model->brandModel->name : $model->brand_model_name;
                        $html = Html::tag('div', $text, ['class' => 'grid-table grid-20']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>


