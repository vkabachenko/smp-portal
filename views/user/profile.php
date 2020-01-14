<?php

use yii\bootstrap\Html;

/* @var $user \app\models\User */
/* @var $returnUrl string */

$this->title = 'Профиль пользователя ' . $user->name;
$this->params['back'] = $returnUrl;

$userAttributes = [
    'name',
    'username',
    'email',
    [
        'label' => 'Зарегистрирован',
        'value' => \Yii::$app->formatter->asDate($user->created_at)
    ]
];

/* @var $master \app\models\Master */
$master = $user->master;
if ($master) {
    $masterAttributes = [
        [
            'label' => 'Должность',
            'value' => $master->main ? 'Главный мастер' : 'Мастер'
        ],
        [
            'label' => 'Телефон',
            'value' => $master->phone
        ],
    ];

    /* @var $workshop \app\models\Workshop */
    $workshop = $master->workshop;
    $workshopAttributes = [
        [
            'label' => 'Мастерская',
            'value' => $workshop->name
        ],
        [
            'label' => 'Телефон1',
            'value' => $workshop->phone1,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone1']]
        ],
        [
            'label' => 'Телефон2',
            'value' => $workshop->phone2,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone2']]
        ],
        [
            'label' => 'Телефон3',
            'value' => $workshop->phone3,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone3']]
        ],
        [
            'label' => 'Телефон4',
            'value' => $workshop->phone4,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone4']]
        ],
        [
            'label' => 'Email1',
            'value' => $workshop->email1,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email1']]
        ],
        [
            'label' => 'Email2',
            'value' => $workshop->email2,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email2']]
        ],
        [
            'label' => 'Email3',
            'value' => $workshop->email3,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email3']]
        ],
        [
            'label' => 'Email4',
            'value' => $workshop->email4,
            'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email4']]
        ],
    ];

    $attributes = array_merge($userAttributes, $masterAttributes, $workshopAttributes);
} else {
    $manager = $user->manager;
    if ($manager) {
        $managerAttributes = [
            [
                'label' => 'Должность',
                'value' => $manager->main ? 'Главный менеджер' : 'Менеджер'
            ],
            [
                'label' => 'Телефон',
                'value' => $manager->phone
            ],
        ];

        /* @var $agency \app\models\Agency */
        $agency = $manager->agency;
        $agencyAttributes = [
            [
                'label' => 'Представительство',
                'value' => $agency->name
            ],
            [
                'label' => 'Производитель',
                'value' => $agency->manufacturer->name
            ],
            [
                'label' => 'Телефон1',
                'value' => $agency->phone1,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone1']]
            ],
            [
                'label' => 'Телефон2',
                'value' => $agency->phone2,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone2']]
            ],
            [
                'label' => 'Телефон3',
                'value' => $agency->phone3,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone3']]
            ],
            [
                'label' => 'Телефон4',
                'value' => $agency->phone4,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone4']]
            ],
            [
                'label' => 'Email1',
                'value' => $agency->email1,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email1']]
            ],
            [
                'label' => 'Email2',
                'value' => $agency->email2,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email2']]
            ],
            [
                'label' => 'Email3',
                'value' => $agency->email3,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email3']]
            ],
            [
                'label' => 'Email4',
                'value' => $agency->email4,
                'captionOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email4']]
            ],
        ];

        $attributes = array_merge($userAttributes, $managerAttributes, $agencyAttributes);
    } else {
        $attributes = $userAttributes;
    }
}
?>

<h1><?= Html::encode($this->title) ?></h1>

<div>
    <?= \yii\widgets\DetailView::widget([
        'model' => $user,
        'attributes' => $attributes
    ]); ?>
</div>


