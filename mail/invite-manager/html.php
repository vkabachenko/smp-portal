<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var \app\models\Manager $manager */

$url = Url::to(['manager-signup/index', 'token' => $manager->invite_token], true);
?>


<h2>Здравствуйте</h2>
<p>
    Вас пригласили в качестве менеджера в представительство
    <strong>
        <?= $manager->agency->name ?>
    </strong>
</p>
<p>
    Для регистрации на портале перейдите по ссылке
    <?= Html::a($url, $url) ?>
</p>
