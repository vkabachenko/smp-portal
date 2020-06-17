<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JobsCatalog */

$this->title = 'Новый вид работ';
$this->params['back'] = ['index', 'agencyId' => $model->agency_id];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
