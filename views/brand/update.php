<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\Brand */

$this->title = 'Редактировать бренд: ' . $model->name;
$this->params['back'] = ['index', 'manufacturerId' => $model->manufacturer_id];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
