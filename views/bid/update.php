<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Редактирование заявки';
$this->params['back'] = ['view', 'id' => $model->id];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
