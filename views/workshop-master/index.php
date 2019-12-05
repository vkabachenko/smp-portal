<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $mastersDataProvider yii\data\ActiveDataProvider */
/* @var $workshop \app\models\Workshop */

$this->title = 'Мастера мастерской ' . $workshop->name;
$this->params['back'] = ['master/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $mastersDataProvider,
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
        <?= Html::a('Пригласить мастера', ['invite'], ['class' => 'btn btn-success']) ?>
    </div>

</div>