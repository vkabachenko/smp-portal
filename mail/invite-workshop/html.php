<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Master $master */

$url = Url::to(['workshop-signup/index', 'token' => $master->user->verification_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Закончите регистрацию мастерской
    <strong>
        <?= $master->workshop->name ?>
    </strong>
</p>
<p>
    Мастер <?= $master->user->name ?>
</p>
<p>
    E-mail мастера <?= $master->user->email ?>
</p>
<p>
    Для этого перейдите по ссылке
    <?= Html::a($url, $url) ?>
</p>
