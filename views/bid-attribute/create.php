<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BidAttribute */

$this->title = 'Добавить поле заявки';
$this->params['back'] = ['index'];
?>
<div class="bid-attribute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
