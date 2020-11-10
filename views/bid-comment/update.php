<?php

/* @var $model \app\models\BidComment */

$this->title = 'Редактировать комментарий';
$this->params['back'] = ['bid-comment/index', 'bidId' => $model->bid_id];
?>

<div>

    <h3><?= $this->title ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


