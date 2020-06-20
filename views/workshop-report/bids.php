<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Bid;
use app\helpers\common\DateHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $reportId int */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Заявки отчета';
$this->params['back'] = ['view', 'id' => $reportId];

?>

<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'header' => 'Заявка',
                'format' => 'raw',
                'value' => function (Bid $bid)  use ($reportId) {
                    /* @var $model app\models\Bid*/
                    $title = sprintf('№ %s от %s, %s',
                        $bid->bid_number,
                        DateHelper::getReadableDate($bid->created_at),
                        $bid->equipment
                    );
                    $html = Html::a($title, [
                        'bid/view',
                        'id' => $bid->id,
                        'returnUrl' => Url::to(['workshop-report/bids', 'reportId' => $reportId])
                    ]);
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>
