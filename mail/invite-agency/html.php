<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Manager $manager */

$url = Url::to(['agency-signup/index', 'token' => $manager->user->verification_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Закончите регистрацию представительства
    <strong>
        <?= $manager->agency->name ?>
    </strong>
</p>
<p>
    Для этого перейдите по ссылке
    <?= Html::a($url, $url) ?>
</p>
