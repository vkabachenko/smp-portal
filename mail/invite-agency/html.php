<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Manager $manager */
/* @var bool $is_independent */

$url = Url::to([
    $is_independent ? 'agency-signup/index-admin' : 'agency-signup/index',
    'token' => $manager->user->verification_token
], true);
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
