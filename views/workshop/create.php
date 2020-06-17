<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Workshop */
/* @var $rules app\models\form\WorkshopRulesForm */


$this->title = 'Новая мастерская';
$this->params['back'] = ['index'];
?>
<div class="workshop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rules' => $rules
    ]) ?>

</div>
