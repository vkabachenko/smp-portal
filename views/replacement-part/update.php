<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReplacementPart */

/* @var $jobsCatalogService \app\services\job\JobsCatalogService */

$this->title = 'Редактирование артикула';
$this->params['back'] = ['index', 'bidId' => $model->bid_id];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
