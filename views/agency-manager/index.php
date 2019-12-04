<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $managersDataProvider yii\data\ActiveDataProvider */
/* @var $agency \app\models\Agency */

$this->title = 'Менеджеры представительства ' . $agency->name;
$this->params['back'] = ['manager/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $managersDataProvider,
        'columns' => [
            'user.name',
            'phone',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


    <div class="form-group">
        <?= Html::a('Пригласить менеджера', ['invite'], ['class' => 'btn btn-success']) ?>
    </div>



</div>