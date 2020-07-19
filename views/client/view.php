<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\Client */

?>
    <span>
        Наименование <?= $model->name ?>
    </span>
<br/>
    <span>
        Телефон <?= $model->clientPhone ?>
    </span>
<br/>
    <?= $this->render('modal/_view', compact('model')) ?>
<br/>


