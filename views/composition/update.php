<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Редактировать комплектность: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Комплектность', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
