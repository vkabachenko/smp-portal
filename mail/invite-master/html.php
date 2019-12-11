<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Master $master */

$url = Url::to(['master-signup/index', 'token' => $master->invite_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Вас пригласили в качестве мастера в мастерскую
    <strong>
        <?= $master->workshop->name ?>
    </strong>
</p>
<p>
    Для регистрации на портале перейдите по ссылке
    <?= Html::a($url, $url) ?>
</p>
