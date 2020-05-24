<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Report;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчеты';
$this->params['back'] = ['manager/index'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'workshop_id',
                'value' => function(Report $report) {
                    return $report->workshop->name;
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
                'header' => 'Отчет',
                'format' => 'raw',
                'value' => function(Report $report) {
                    return Html::a('Скачать', ['download/default', 'filename' => 'report.xlsx', 'path' => $report->report_filename]);
                }
            ],
        ],
    ]); ?>


</div>
