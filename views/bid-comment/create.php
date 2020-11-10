<?php

/* @var $model \app\models\BidComment */

$this->title = 'Новый комментарий';
$this->params['back'] = ['bid-comment/index', 'bidId' => $model->bid_id];
?>

<div>

    <h3><?= $this->title ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

