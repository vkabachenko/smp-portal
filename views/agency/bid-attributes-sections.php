<?php

use yii\bootstrap\Html;
use app\models\Bid;


/* @var $this yii\web\View */
/* @var $agency \app\models\Agency */


$this->title = 'Настроить расположение полей заявки для представительства ' . $agency->name;
$this->params['back'] = ['agency/update', 'id' => $agency->id];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>


</div>


