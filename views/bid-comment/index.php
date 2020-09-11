<?php

use yii\bootstrap\Html;
use yii\widgets\ListView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $bidId int */
/* @var $private bool */
$this->title = $private ? 'Служебные комментарии' : 'Комментарии';
$this->params['back'] = ['bid/view', 'id' => $bidId];
?>

<h3><?= $this->title ?></h3>

<div class="form-group">
    <?= Html::a('Новый комментарий', ['bid-comment/create', 'bidId' => $bidId, 'private' => $private], ['class' => 'btn btn-success']) ?>
</div>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_comment',
    'summary'=> ''
]);
