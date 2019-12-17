<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Workshop */
/* @var $rules app\models\form\WorkshopRulesForm */


$this->title = 'Новое представительство';
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>