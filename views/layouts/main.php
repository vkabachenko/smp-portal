<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\widgets\BackWidget\BackWidget;

AppAsset::register($this);

if (Yii::$app->user->isGuest) {
    $commonItems = [
        ['label' => 'Вход', 'url' => ['/site/login']],
        ['label' => 'Регистрация представительства', 'url' => ['/site/signup-agency']],
        ['label' => 'Регистрация мастерской', 'url' => ['/site/signup-workshop']],
    ];
} else {
    $commonItems = [
        '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>'
    ];
}


// Menu items
/*$commonItems = [
    Yii::$app->user->isGuest ? (
    ['label' => 'Вход', 'url' => ['/site/login']]
    ) : (
        '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>'
    )
];*/

$customItems = [];

if (\Yii::$app->user->can('admin')) {
    $customItems = [
            [
                'label' => 'Заявки',
                'url' => ['bid/index', 'title' => 'Личный кабинет администратора']
            ],
        [
            'label' => 'Справочники',
            'items' => [
                [
                    'label' => 'Мастерские',
                    'url' => ['workshop/index']
                ],
                [
                    'label' => 'Мастера',
                    'url' => ['master-crud/index']
                ],
                [
                    'label' => 'Менеджеры',
                    'url' => ['manager-crud/index']
                ],
                [
                    'label' => 'Производители',
                    'url' => ['manufacturer/index']
                ],
                [
                    'label' => 'Соответствие брендов',
                    'url' => ['brand-correspondence/index']
                ],
                [
                    'label' => 'Комплектность',
                    'url' => ['composition/index']
                ],
                [
                    'label' => 'Состояния',
                    'url' => ['condition/index']
                ],
                [
                    'label' => 'Статусы ремонта',
                    'url' => ['repair-status/index']
                ],
                [
                    'label' => 'Статусы гарантии',
                    'url' => ['warranty-status/index']
                ],
                [
                    'label' => 'Статусы заявки',
                    'url' => ['bid-status/index']
                ],
            ]
        ],
    ];
}

if (\Yii::$app->user->can('manager')) {
    $customItems = [
        [
            'label' => 'Заявки',
            'url' => ['bid/index', 'title' => 'Личный кабинет менеджера']
        ],
        [
            'label' => 'Шаблоны',
            'items' => [
                [
                    'label' => 'Excel актов технического состояния',
                    'url' => ['manufacturer/index-template']
                ],
                [
                    'label' => 'Писем актов технического состояния',
                    'url' => ['email-template/update']
                ],
            ]
        ],
    ];
}

$items = array_merge($customItems, $commonItems);

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
        'items' => $items
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= BackWidget::widget([
            'backLink' => isset($this->params['back']) ? $this->params['back'] : null,
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; SMP <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
