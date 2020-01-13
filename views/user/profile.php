<?php

use yii\bootstrap\Html;

/* @var $user \app\models\User */
/* @var $returnUrl string */

$this->title = 'Профиль пользователя ' . $user->name;
$this->params['back'] = $returnUrl;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div>
    <?= \yii\widgets\DetailView::widget([
        'model' => $user,
        'attributes' => [
            'name',
            'username',
            'email',
            [
                'label' => 'Зарегистрирован',
                'value' => \Yii::$app->formatter->asDate($user->created_at)
            ]
        ]
    ]); ?>
</div>


