<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $agencyDataProvider yii\data\ActiveDataProvider */
/* @var $workshop \app\models\Workshop */

$this->title = 'Представительства мастерской  ' . $workshop->name;
$this->params['back'] = ['master/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $agencyDataProvider,
        'rowOptions'=>function (\app\models\Agency $agency) use ($workshop)
            {if (!\app\models\AgencyWorkshop::getActive($agency, $workshop)) {return ['class'=>'disabled'];} },
        'columns' => [
            'name',
            [
                'attribute' => 'manufacturer_id',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Agency */
                    $html = $model->manufacturer->name;
                    return $html;
                },
            ],
        ],
    ]); ?>


</div>