<?php

/* @var $gridAttributes array */

use app\models\additional\BidGridAttributes;
use yii\bootstrap\Html;
use kartik\sortable\Sortable;

$this->title = 'Настройте расположение полей для таблицы заявок ';
$this->params['back'] = ['index'];

$gridAttributes = BidGridAttributes::convert($gridAttributes);

?>

<div>
    <h2><?= $this->title ?></h2>
    <p>Перетаскивайте поля для достижения нужного порядка</p>
    <?= Html::beginForm(['save-grid-attributes']) ?>
    <div class="row">
        <div class="col-xs-6 grid-attributes-header">
            Поле
        </div>
        <div class="col-xs-2 grid-attributes-header">
            Компьютер
        </div>
        <div class="col-xs-2 grid-attributes-header">
            Планшет
        </div>
        <div class="col-xs-2 grid-attributes-header">
            Телефон
        </div>
    </div>

        <?= Sortable::widget([
            'items'=> $gridAttributes
        ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-grid-attributes']) ?>
    </div>

    <?= Html::endForm() ?>
</div>
