<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BidAttribute */

$this->title = 'Редактировать поле заявки ' . \app\models\Bid::EDITABLE_ATTRIBUTES[$model->attribute];
$this->params['back'] = ['index'];
?>
<div class="bid-attribute-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
