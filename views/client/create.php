<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\Client */

$this->title = 'Новый клиент';
$this->params['back'] = ['index'];

\app\assets\ClientAsset::register($this);
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'client' => $model,
    ]) ?>

</div>
