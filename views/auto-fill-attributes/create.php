<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AutoFillAttributes */

$this->title = 'Новое правило автозаполнения';
$this->params['back'] = ['index'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
