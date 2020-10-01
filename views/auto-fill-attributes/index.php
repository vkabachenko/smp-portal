<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\AutoFillAttributes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Правила автозаполнения полей заявки';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новое правило', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'decision_workshop_status_id',
                'value' => function (AutoFillAttributes $model) {
                    $value = $model->decision_workshop_status_id ? $model->decisionWorkshopStatus->name : null;
                    return $value;
                },
            ],
            [
                'attribute' => 'decision_agency_status_id',
                'value' => function (AutoFillAttributes $model) {
                    $value = $model->decision_agency_status_id ? $model->decisionAgencyStatus->name : null;
                    return $value;
                },
            ],
            [
                'attribute' => 'status_id',
                'value' => function (AutoFillAttributes $model) {
                    $value = $model->status_id ? $model->status->name : null;
                    return $value;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
