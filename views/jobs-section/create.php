<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JobsSection */

$this->title = 'Новый раздел работ';
$this->params['back'] = ['index', 'agencyId' => $model->agency_id];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
