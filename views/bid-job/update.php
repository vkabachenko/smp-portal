<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BidJob */
/* @var $bid app\models\Bid */
/* @var $jobsCatalog \app\models\JobsCatalog */

/* @var $jobsCatalogService \app\services\job\JobsCatalogService */

$this->title = 'Редактирование работы';
$this->params['back'] = ['index', 'bidId' => $model->bid_id];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'bid' => $bid,
        'jobsCatalog' => $jobsCatalog,
        'jobsCatalogService' => $jobsCatalogService
    ]) ?>

</div>
