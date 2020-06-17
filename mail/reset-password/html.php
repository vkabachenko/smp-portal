<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\User $user */

$url = Url::to(['site/create-password', 'token' => $user->password_reset_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Для создания нового пароля к вашему аккаунту на сайте <a href="https:://garantportal.ru">garantportal.ru</a> перейдите по ссылке <?= Html::a($url, $url) ?>
</p>

