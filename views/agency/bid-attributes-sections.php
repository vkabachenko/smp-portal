<?php

use yii\bootstrap\Html;
use kartik\sortable\Sortable;


/* @var $this yii\web\View */
/* @var $agency \app\models\Agency */


$this->title = 'Настроить расположение полей заявки для представительства ' . $agency->name;
$this->params['back'] = ['agency/update', 'id' => $agency->id];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-4">
            <?= Sortable::widget([
                'connected'=>true,
                'items'=>[
                    ['content'=>'From Item 1'],
                    ['content'=>'From Item 2'],
                    ['content'=>'From Item 3'],
                    ['content'=>'From Item 4'],
                ]
            ]); ?>
        </div>

        <div class="col-xs-4">
            <?= Sortable::widget([
                'connected'=>true,
                'items'=>[
                    ['content'=>'Item 4'],
                    ['content'=>'Item 5'],
                    ['content'=>'Item 6'],
                    ['content'=>'Item 7'],
                ]
            ]); ?>
        </div>

        <div class="col-xs-4">
            <?= Sortable::widget([
                'connected'=>true,
                'items'=>[
                    ['content'=>'From 1'],
                    ['content'=>'From 2'],
                    ['content'=>'From 3'],
                ]
            ]); ?>
        </div>

    </div>







</div>


