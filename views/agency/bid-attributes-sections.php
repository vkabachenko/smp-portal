<?php

use yii\bootstrap\Html;
use kartik\sortable\Sortable;
use app\models\additional\BidSection;


/* @var $this yii\web\View */
/* @var $agency \app\models\Agency */
/* @var $bidSection \app\models\additional\BidSection */


$this->title = 'Настроить расположение полей заявки для представительства ' . $agency->name;
$this->params['back'] = ['agency/update', 'id' => $agency->id];

$items1 = array_map([BidSection::class, 'callbackSortable'], $bidSection->section1);
$items2 = array_map([BidSection::class, 'callbackSortable'], $bidSection->section2);
$items3 = array_map([BidSection::class, 'callbackSortable'], $bidSection->section3);
$items4 = array_map([BidSection::class, 'callbackSortable'], $bidSection->section4);
$items5 = array_map([BidSection::class, 'callbackSortable'], $bidSection->section5);
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <div>
        Расположите атрибуты в желаемом порядке путем их перетаскивания между разделами или внутри разделов
    </div>
    <div class="row">
        <div class="col-xs-3 attribute-section1">
            <h3>Раздел 1</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items1
            ]); ?>
        </div>

        <div class="col-xs-3 attribute-section2">
            <h3>Раздел 2</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items2
            ]); ?>
        </div>

        <div class="col-xs-2 attribute-section3">
            <h3>Раздел 3</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items3
            ]); ?>
        </div>

        <div class="col-xs-2 attribute-section4">
            <h3>Раздел 4</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items4
            ]); ?>
        </div>

        <div class="col-xs-2 attribute-section5">
            <h3>Раздел 5</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items5
            ]); ?>
        </div>
    </div>

    <div>
        <?= Html::a('Сохранить',
            ['save-attributes-sections', 'agencyId' =>$agency->id],
            ['class' => 'save-attribute-sections btn btn-success'])
        ?>
    </div>
</div>











