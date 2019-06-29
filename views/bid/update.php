<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Редактирование заявки';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр  заявки', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
