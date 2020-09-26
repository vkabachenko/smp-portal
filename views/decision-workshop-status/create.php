<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $autoFilledAttributes array */
/* @var $model \app\models\DecisionWorkshopStatus */

$this->title = 'Новый статус решения мастерской';
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
