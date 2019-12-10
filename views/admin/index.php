<?php

use app\helpers\bid\TitleHelper;
use yii\bootstrap\Html;

$this->title = TitleHelper::getTitle(\Yii::$app->user->identity);;

?>
<h2 style="text-align: center;"> <?= $this->title ?> </h2>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Заявки', ['bid/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Мастерские', ['workshop/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Производители', ['manufacturer/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Соответствие брендов', ['brand-correspondence/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Комплектность', ['composition/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Состояния', ['condition/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы ремонта', ['repair-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы гарантии', ['warranty-status/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Статусы заявки', ['bid-status/index'], ['class' => 'list-group-item center-menu-item']) ?>

    </div>
</div>
