<?php

use yii\bootstrap\Html;
use yii\widgets\ListView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $bidId int */
$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['bid/index']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр  заявки', 'url' => ['bid/view', 'id' => $bidId]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h3><?= $this->title ?></h3>

<div class="form-group">
    <?= Html::a('Новый комментарий', ['bid-comment/create', 'bidId' => $bidId], ['class' => 'btn btn-success']) ?>
</div>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_comment',
    'summary'=> ''
]);
