<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Новый статус заявки';
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
