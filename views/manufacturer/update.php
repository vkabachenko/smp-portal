<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturer */

$this->title = 'Редактировать производителя: ' . $model->name;
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
