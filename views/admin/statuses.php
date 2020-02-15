<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Статусы';
$this->params['back'] = ['admin/catalogs'];
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Ремонта', ['repair-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Гарантии', ['warranty-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Решения мастерской', ['decision-workshop-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Решения представительства', ['decision-agency-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
    </div>
</div>
