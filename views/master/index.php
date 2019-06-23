<?php

use app\models\Bid;
use app\models\search\BidSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет мастера';
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <h2>Личный кабинет  мастера <?= \Yii::$app->user->identity->username ?></h2>

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
                        $text = date('d.m.Y', strtotime($model->created_at));
                        $html = Html::a($text, ['update', 'id' => $model->id]);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'manufacturer_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $html = Html::tag('div', $model->manufacturer->name, ['style'=> 'min-width: 25%; white-space: normal; word-wrap: break-word']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'brand_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = $model->brand_id ? $model->brand->name : '';
                        $html = Html::tag('div', $text, ['style'=> 'min-width: 25%; white-space: normal; word-wrap: break-word']);
                        return $html;
                    },
                ],
                [
                    'attribute' => 'brand_model_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /* @var $model app\models\Bid */
                        $text = $model->brand_model_id ? $model->brandModel->name : $model->brand_model_name;
                        $html = Html::tag('div', $text, ['style'=> 'min-width: 25%; white-space: normal; word-wrap: break-word']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
