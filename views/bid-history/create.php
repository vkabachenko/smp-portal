<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BidHistory */
/* @var $uploadForm \app\models\form\MultipleUploadForm */

$this->title = 'Новый этап заявки';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['bid/index']];
$this->params['breadcrumbs'][] = ['label' => 'История заявки', 'url' => ['bid-history/index', 'bidId' => $model->bid_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'uploadForm' => $uploadForm
    ]) ?>

</div>
