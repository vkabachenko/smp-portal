<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $manager app\models\Manager */
/* @var $user app\models\User */

$this->title = 'Редактировать менеджера: ' . $user->name;
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'manager' => $manager,
        'user' => $user
    ]) ?>

</div>
