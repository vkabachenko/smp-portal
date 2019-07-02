<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Новый статус гарантии';
$this->params['breadcrumbs'][] = ['label' => 'Статусы гарантии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
