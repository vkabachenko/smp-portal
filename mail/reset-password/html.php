<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\User $user */

$url = Url::to(['site/reset-password', 'token' => $user->password_reset_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Для восстановления вашего пароля на сайте SMP-портал перейдите по ссылке <?= Html::a($url, $url) ?>
</p>

