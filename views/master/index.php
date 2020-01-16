<?php

use app\helpers\bid\TitleHelper;
use yii\bootstrap\Html;

/* @var $news \app\models\News[] */
/* @var $newsShowServices \app\services\news\NewsShowService[] */

$this->title = TitleHelper::getTitle(\Yii::$app->user->identity);
?>

<?php $this->beginBlock('header'); ?>
    <h1 style="text-align: center;"> <?= $this->title ?> </h1>
<?php $this->endBlock(); ?>

<div class="center-menu-container">
    <div class="list-group center-menu">
        <?= Html::a('Заявки', ['bid/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Профиль', ['master/profile'], ['class' => 'list-group-item center-menu-item']) ?>

        <?php if (\Yii::$app->user->can('manageWorkshops')): ?>

            <?= Html::a('Представительства', ['workshop-agency/agencies'], ['class' => 'list-group-item center-menu-item']) ?>
            <?= Html::a('Мастера', ['workshop-master/masters'], ['class' => 'list-group-item center-menu-item']) ?>

        <?php endif; ?>
        <?= Html::a('Написать разработчику', '#', ['class' => 'list-group-item center-menu-item btn-feedback']) ?>
    </div>
</div>

<?php $this->beginBlock('news'); ?>
    <?= $this->render('//layouts/partial/news', compact('news', 'newsShowServices')); ?>
<?php $this->endBlock(); ?>