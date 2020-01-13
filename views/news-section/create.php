<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSection */

$this->title = 'Создать раздел новости';
$this->params['back'] = ['index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
