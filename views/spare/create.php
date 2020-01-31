<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Spare */
/* @var $bidId int */

$this->title = 'Новая запчасть';
$this->params['back'] = ['index', 'bidId' => $bidId];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
