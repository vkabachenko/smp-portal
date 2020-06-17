<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Report;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчеты';
$this->params['back'] = ['master/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый отчет', ['select-agency'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function(Report $report) {
                return ['class' => ($report->is_transferred ? 'disabled enabled-events' : '')];
            },
        'columns' => [
            [
                'attribute' => 'agency_id',
                'value' => function(Report $report) {
                    return $report->agency->name;
                }
            ],
            'report_nom',
            [
                'attribute' => 'report_date',
                'value' => function(Report $report) {
                    return \Yii::$app->formatter->asDate($report->report_date);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>


</div>
