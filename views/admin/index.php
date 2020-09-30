<?php

use app\helpers\bid\TitleHelper;
use yii\bootstrap\Html;

$this->title = TitleHelper::getTitle(\Yii::$app->user->identity);;

?>
<h1 style="text-align: center;"> <?= $this->title ?> </h1>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Заявки', ['bid/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Автозаполнение полей заявки', ['auto-fill-attributes/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Поля заявки', ['bid-attribute/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Клиенты', ['client/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Мастерские', ['workshop/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Представительства', ['agency/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Новости', ['news/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Справочники', ['admin/catalogs'], ['class' => 'list-group-item center-menu-item']) ?>

    </div>
</div>
