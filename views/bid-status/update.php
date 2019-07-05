<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Редактировать статус заявки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статусы заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
