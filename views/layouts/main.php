<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\bootstrap\Modal;


AppAsset::register($this);

if (Yii::$app->user->isGuest) {
    $commonItems = [
        ['label' => 'Вход', 'url' => ['/site/login']],
    ];
} else {
    $commonItems = [
        '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->name . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>'
    ];
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $commonItems
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php if (isset($this->blocks['news'])): ?>
            <?php if (isset($this->blocks['header'])): ?>
                <?= $this->blocks['header'] ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <?= $this->render('partial/common', compact('content')); ?>
                </div>
                <div class="col-xs-12 col-sm-6 news-wrap">
                    <?= $this->blocks['news'] ?>
                </div>
            </div>
        <?php else: ?>
            <?= $this->render('partial/common', compact('content')); ?>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <div>
        <div class="col-xs-6">
            <?php if (!\Yii::$app->user->isGuest): ?>
                <?php Modal::begin([
                    'id' => 'feedback-modal',
                    'header' => '<h3>Заполните форму обратной связи</h3>',
                    'toggleButton' => [
                        'tag' => 'a',
                        'label' => 'Написать разработчику',
                        'style' => 'cursor: pointer'
                    ],
                ]);

                echo $this->render('partial/feedback');

                Modal::end(); ?>
            <?php endif; ?>
        </div>
        <div class="col-xs-6">
            <p class="pull-left">&copy; SMP <?= date('Y') ?></p>
        </div>


    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
