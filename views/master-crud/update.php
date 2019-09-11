<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $master app\models\Master */
/* @var $user app\models\User */

$this->title = 'Редактировать мастера: ' . $user->name;
$this->params['back'] = ['index'];
?>
<div class="master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'master' => $master,
        'user' => $user
    ]) ?>

</div>
