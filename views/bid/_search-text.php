<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;
use app\models\Bid;
use app\models\Condition;
use app\models\RepairStatus;
use app\models\WarrantyStatus;
use app\models\BidStatus;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\search\BidSearch */
?>

<div class="bid-search-text">

    <span class="bid-search-head-main">Фильтр:</span>

    <?php if($model->created_at_from): ?>
        <span class="bid-search-head">
            Дата создания
        </span>
        <span class="bid-search-content">
            <?= $model->created_at_from . ' - ' . $model->created_at_to ?>
        </span>
    <?php endif; ?>

    <?php if($model->manufacturer_id): ?>
        <span class="bid-search-head">
            Производитель
        </span>
        <span class="bid-search-content">
            <?= $model->manufacturer->name ?>
        </span>
    <?php endif; ?>

    <?php if($model->brand_name): ?>
        <span class="bid-search-head">
            Бренд
        </span>
        <span class="bid-search-content">
            <?= $model->brand_name ?>
        </span>
    <?php endif; ?>

    <?php if($model->equipment): ?>
        <span class="bid-search-head">
            Оборудование
        </span>
        <span class="bid-search-content">
            <?= $model->equipment ?>
        </span>
    <?php endif; ?>

    <?php if($model->brand_model_name): ?>
        <span class="bid-search-head">
            Модель
        </span>
        <span class="bid-search-content">
            <?= $model->brand_model_name ?>
        </span>
    <?php endif; ?>

    <?php if($model->composition_name): ?>
        <span class="bid-search-head">
            Комплектация
        </span>
        <span class="bid-search-content">
            <?= $model->composition_name ?>
        </span>
    <?php endif; ?>

    <?php if($model->serial_number): ?>
        <span class="bid-search-head">
            Серийный номер
        </span>
        <span class="bid-search-content">
            <?= $model->serial_number ?>
        </span>
    <?php endif; ?>

    <?php if($model->vendor_code): ?>
        <span class="bid-search-head">
            Артикул
        </span>
        <span class="bid-search-content">
            <?= $model->vendor_code ?>
        </span>
    <?php endif; ?>

    <?php if($model->client_name): ?>
        <span class="bid-search-head">
            Клиент
        </span>
        <span class="bid-search-content">
            <?= $model->client_name ?>
        </span>
    <?php endif; ?>

    <?php if($model->client_phone): ?>
        <span class="bid-search-head">
            Телефон клиента
        </span>
        <span class="bid-search-content">
            <?= $model->client_phone ?>
        </span>
    <?php endif; ?>

    <?php if($model->client_address): ?>
        <span class="bid-search-head">
            Адрес клиента
        </span>
        <span class="bid-search-content">
            <?= $model->client_address ?>
        </span>
    <?php endif; ?>

    <?php if($model->treatment_type): ?>
        <span class="bid-search-head">
            Тип обращения
        </span>
        <span class="bid-search-content">
            <?= Bid::TREATMENT_TYPES[$model->treatment_type] ?>
        </span>
    <?php endif; ?>

    <?php if($model->purchase_date_from): ?>
        <span class="bid-search-head">
            Дата покупки
        </span>
        <span class="bid-search-content">
            <?= $model->purchase_date_from . ' - ' . $model->purchase_date_to ?>
        </span>
    <?php endif; ?>

    <?php if($model->purchase_date_from): ?>
        <span class="bid-search-head">
            Дата обращения
        </span>
        <span class="bid-search-content">
            <?= $model->application_date_from . ' - ' . $model->application_date_to ?>
        </span>
    <?php endif; ?>

    <?php if($model->warranty_number): ?>
        <span class="bid-search-head">
            Номер гарантии
        </span>
        <span class="bid-search-content">
            <?= $model->warranty_number ?>
        </span>
    <?php endif; ?>

    <?php if($model->bid_number): ?>
        <span class="bid-search-head">
            Номер заявки
        </span>
        <span class="bid-search-content">
            <?= $model->bid_number ?>
        </span>
    <?php endif; ?>

    <?php if($model->bid_1C_number): ?>
        <span class="bid-search-head">
            Номер заявки в 1С
        </span>
        <span class="bid-search-content">
            <?= $model->bid_1C_number ?>
        </span>
    <?php endif; ?>

    <?php if($model->bid_manufacturer_number): ?>
        <span class="bid-search-head">
            Номер заявки у производителя
        </span>
        <span class="bid-search-content">
            <?= $model->bid_manufacturer_number ?>
        </span>
    <?php endif; ?>

    <?php if($model->condition_id): ?>
        <span class="bid-search-head">
            Состояние
        </span>
        <span class="bid-search-content">
            <?= $model->condition->name ?>
        </span>
    <?php endif; ?>

    <?php if($model->defect): ?>
        <span class="bid-search-head">
            Заявленная неисправность
        </span>
        <span class="bid-search-content">
            <?= $model->defect ?>
        </span>
    <?php endif; ?>

    <?php if($model->diagnostic): ?>
        <span class="bid-search-head">
            Результат диагностики
        </span>
        <span class="bid-search-content">
            <?= $model->diagnostic ?>
        </span>
    <?php endif; ?>

    <?php if($model->repair_status_id): ?>
        <span class="bid-search-head">
            Статус ремонта
        </span>
        <span class="bid-search-content">
            <?= $model->repairStatus->name ?>
        </span>
    <?php endif; ?>

    <?php if($model->warranty_status_id): ?>
        <span class="bid-search-head">
            Статус гарантии
        </span>
        <span class="bid-search-content">
            <?= $model->warrantyStatus->name ?>
        </span>
    <?php endif; ?>

    <?php if($model->status_id): ?>
        <span class="bid-search-head">
            Статус заявки
        </span>
        <span class="bid-search-content">
            <?= $model->status->name ?>
        </span>
    <?php endif; ?>

</div>

<?php
$script = <<<JS
if ($('.bid-search-content').length === 0) {
    $('.bid-search-head-main').hide();
}
JS;

$this->registerJs($script);



