<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Справочники';
$this->params['back'] = ['admin/index'];
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Производители', ['manufacturer/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Соответствие брендов', ['brand-correspondence/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Комплектность', ['composition/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Состояния', ['condition/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы ремонта', ['repair-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы гарантии', ['warranty-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы заявки', ['bid-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
    </div>
</div>
