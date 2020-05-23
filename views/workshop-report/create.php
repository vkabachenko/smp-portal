<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $report app\models\Report */


$this->title = 'Новый отчет';
$this->params['back'] = ['index'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'report' => $report,
    ]) ?>

</div>
