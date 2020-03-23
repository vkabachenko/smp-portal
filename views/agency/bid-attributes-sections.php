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
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <div>
        Расположите атрибуты в желаемом порядке путем их перетаскивания между разделами или внутри разделов
    </div>
    <div class="row">
        <div class="col-xs-4 attribute-section1">
            <h3>Раздел 1</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items1
            ]); ?>
        </div>

        <div class="col-xs-4 attribute-section2">
            <h3>Раздел 2</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items2
            ]); ?>
        </div>

        <div class="col-xs-4 attribute-section3">
            <h3>Раздел 3</h3>
            <?= Sortable::widget([
                'connected'=>true,
                'items'=> $items3
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

    <?php

    $script = <<<JS
$(function() {
    $('.save-attribute-sections').click(function(evt) {
        evt.preventDefault();
        
        section1 = [];
        $('.attribute-section1 li').each(function() {
            section1.push($(this).data('attribute'));
        });
        
        section2 = [];
        $('.attribute-section2 li').each(function() {
            section2.push($(this).data('attribute'));
        });
        
        section3 = [];
        $('.attribute-section3 li').each(function() {
            section3.push($(this).data('attribute'));
        });
        
        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            data: {
                'section1': section1,
                'section2': section2,
                'section3': section3,
            }
        });
    })
    
});

JS;

    $this->registerJs($script);

    ?>










