<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $managersDataProvider yii\data\ActiveDataProvider */
/* @var $agency \app\models\Agency */

$this->title = 'Менеджеры представительства ' . $agency->name;
$this->params['back'] = \Yii::$app->user->can('admin') ? ['agency/index'] : \yii\helpers\Url::previous();
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $managersDataProvider,
        'rowOptions'=>function (\app\models\Manager $manager)
        {if (!$manager->isActive()) {return ['class'=>'disabled enabled-events'];} },
        'columns' => [
            'user.name',
            [
                'attribute' => 'main',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Manager */
                    $html = $model->main ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                },
            ],
            'phone',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', $url. '&agencyId=' . $model->agency_id, ['class' => 'glyphicon glyphicon-pencil']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', $url. '&agencyId=' . $model->agency_id,
                            ['class' => 'glyphicon glyphicon-trash', 'data' => ['method' => 'post']]);
                    }
                ],
            ],
        ],
    ]); ?>




</div>