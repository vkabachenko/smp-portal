<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Редактировать состояние: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Состояния', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
