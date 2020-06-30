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

    <?php if($model->master_id): ?>
        <span class="bid-search-head">
            Мастер
        </span>
        <span class="bid-search-content">
            <?= $model->master->user->name ?>
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

    <?php if($model->client_email): ?>
        <span class="bid-search-head">
            Email клиента
        </span>
        <span class="bid-search-content">
            <?= $model->client_email ?>
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

    <?php if($model->client_type): ?>
        <span class="bid-search-head">
            Тип клиента
        </span>
        <span class="bid-search-content">
            <?= \app\models\Client::CLIENT_TYPES[$model->client_type] ?>
        </span>
    <?php endif; ?>

    <?php if($model->saler_name): ?>
        <span class="bid-search-head">
            Продавец
        </span>
        <span class="bid-search-content">
            <?= $model->saler_name ?>
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

    <?php if($model->application_date_from): ?>
        <span class="bid-search-head">
            Дата обращения
        </span>
        <span class="bid-search-content">
            <?= $model->application_date_from . ' - ' . $model->application_date_to ?>
        </span>
    <?php endif; ?>

    <?php if($model->date_manufacturer_from): ?>
        <span class="bid-search-head">
            Дата принятия в ремонт для представительства
        </span>
        <span class="bid-search-content">
            <?= $model->date_manufacturer_from . ' - ' . $model->date_manufacturer_to ?>
        </span>
    <?php endif; ?>

    <?php if($model->date_completion_from): ?>
        <span class="bid-search-head">
            Дата готовности
        </span>
        <span class="bid-search-content">
            <?= $model->date_completion_from . ' - ' . $model->date_completion_to ?>
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

    <?php if($model->defect_manufacturer): ?>
        <span class="bid-search-head">
            Заявленная неисправность для представительства
        </span>
        <span class="bid-search-content">
            <?= $model->defect_manufacturer ?>
        </span>
    <?php endif; ?>

    <?php if($model->is_warranty_defect): ?>
        <span class="bid-search-head">
            Дефект гарантийный
        </span>
        <span class="bid-search-content">
            <?= $model->is_warranty_defect ?>
        </span>
    <?php endif; ?>

    <?php if($model->is_repair_possible): ?>
        <span class="bid-search-head">
            Проведение ремонта возможно
        </span>
        <span class="bid-search-content">
            <?= $model->is_repair_possible ?>
        </span>
    <?php endif; ?>

    <?php if($model->is_for_warranty): ?>
        <span class="bid-search-head">
            Подано на гарантию
        </span>
        <span class="bid-search-content">
            <?= $model->is_for_warranty ?>
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

    <?php if($model->diagnostic_manufacturer): ?>
        <span class="bid-search-head">
            Результат диагностики для представительства
        </span>
        <span class="bid-search-content">
            <?= $model->diagnostic_manufacturer ?>
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

    <?php if($model->repair_recommendations): ?>
        <span class="bid-search-head">
            Рекомендации по ремонту
        </span>
        <span class="bid-search-content">
            <?= $model->repair_recommendations ?>
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

    <?php if($model->comment): ?>
        <span class="bid-search-head">
            Дополнительные отметки
        </span>
        <span class="bid-search-content">
            <?= $model->comment ?>
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




