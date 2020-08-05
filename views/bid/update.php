<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $hints array */

$this->title = 'Редактирование заявки';
$this->params['back'] = ['view', 'id' => $model->id];
?>
<div>

    <h2><?= sprintf('%s (%s %s)', Html::encode($this->title), $model->bid_number, $model->bid_1C_number) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'hints' => $hints
    ]) ?>

</div>
