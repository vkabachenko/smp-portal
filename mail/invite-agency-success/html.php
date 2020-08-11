<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Master $master */

$url = Url::to(['site/login'], true);
?>


<h2>Здравствуйте</h2>
<p>
    Ваша заявка на регистрацию представительства одобрена.
<p>
    Перейдите по ссылке <?= Html::a($url, $url) ?> и введите для входа свой e-mail и пароль
</p>
