<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Agency;

/* @var $this yii\web\View */
/* @var $agencyDataProvider yii\data\ActiveDataProvider */
/* @var $workshop \app\models\Workshop */

$this->title = 'Представительства мастерской  ' . $workshop->name;
$this->params['back'] = ['workshop/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $agencyDataProvider,
        'rowOptions'=>function (\app\models\Agency $agency) use ($workshop)
            {if (!\app\models\AgencyWorkshop::getActive($agency, $workshop)) {return ['class'=>'disabled enabled-events'];} },
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
            [
                'header' => 'Менеджеры',
                'format' => 'raw',
                'value' => function (Agency $agency) {
                    return Html::a('Менеджеры', ['agency-manager/all-managers', 'agencyId' => $agency->id]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{toggle}',
                'buttons' => [
                    'toggle' => function ($url, $model, $key) use ($workshop) {
                        return Html::a('', ['toggle-active', 'agencyId' => $model->id, 'workshopId' => $workshop->id],
                            ['class' => 'glyphicon glyphicon-repeat']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>