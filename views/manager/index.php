<?php

use app\helpers\bid\TitleHelper;
use yii\bootstrap\Html;

/* @var $news \app\models\News[] */

$this->title = TitleHelper::getTitle(\Yii::$app->user->identity);
?>

<?php $this->beginBlock('header'); ?>
    <h1 style="text-align: center;"> <?= $this->title ?> </h1>
<?php $this->endBlock(); ?>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Заявки', ['bid/index'], ['class' => 'list-group-item center-menu-item']) ?>
        <?= Html::a('Профиль', ['manager/profile'], ['class' => 'list-group-item center-menu-item']) ?>

        <?php if (\Yii::$app->user->can('updateAgency')): ?>

            <?= Html::a('Шаблоны - excel', ['manufacturer/index-template'], ['class' => 'list-group-item center-menu-item']) ?>
            <?= Html::a('Шаблоны - письмо', ['email-template/update'], ['class' => 'list-group-item center-menu-item']) ?>
            <?= Html::a('Мастерcкие', ['agency-workshop/workshops'], ['class' => 'list-group-item center-menu-item']) ?>
            <?= Html::a('Менеджеры', ['agency-manager/managers'], ['class' => 'list-group-item center-menu-item']) ?>

        <?php endif; ?>
        <?= Html::a('Написать разработчику', '#', ['class' => 'list-group-item center-menu-item btn-feedback']) ?>
    </div>
</div>

<?php $this->beginBlock('news'); ?>
    <?= $this->render('//layouts/partial/news', compact('news')); ?>
<?php $this->endBlock(); ?>

