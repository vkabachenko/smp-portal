<?php

use app\models\Master;
use app\models\User;
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
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-group">
        <label class="control-label">Дата создания заявки</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'created_at_from',
            'attribute2' => 'created_at_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ]) ?>
    </div>

    <?= $form->field($model, 'manufacturer_id')->dropDownList(Manufacturer::manufacturersAsMap(),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'brand_name') ?>

    <?= $form->field($model, 'equipment') ?>

    <?= $form->field($model, 'brand_model_name') ?>

    <?= $form->field($model, 'composition_name') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'vendor_code') ?>

    <?= $form->field($model, 'master_id')->dropDownList(Master::mastersAsMap(\Yii::$app->user->identity),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'client_name') ?>

    <?= $form->field($model, 'client_phone') ?>

    <?= $form->field($model, 'client_address') ?>

    <?= $form->field($model, 'treatment_type')->dropDownList(Bid::TREATMENT_TYPES ,['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'saler_name') ?>

    <div class="form-group">
        <label class="control-label">Дата покупки</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'purchase_date_from',
            'attribute2' => 'purchase_date_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ]) ?>
    </div>

    <div class="form-group">
        <label class="control-label">Дата обращения</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'application_date_from',
            'attribute2' => 'application_date_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ]) ?>
    </div>

    <div class="form-group">
        <label class="control-label">Дата принятия в ремонт для представительства</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'date_manufacturer_from',
            'attribute2' => 'date_manufacturer_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ]) ?>
    </div>

    <div class="form-group">
        <label class="control-label">Дата готовности</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'date_completion_from',
            'attribute2' => 'date_completion_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
        ]) ?>
    </div>

    <?= $form->field($model, 'warranty_number') ?>

    <?= $form->field($model, 'bid_number') ?>

    <?= $form->field($model, 'bid_1C_number') ?>

    <?= $form->field($model, 'bid_manufacturer_number') ?>

    <?= $form->field($model, 'condition_id')->dropDownList(Condition::conditionsAsMap(),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'client_type')->dropDownList(Bid::CLIENT_TYPES, ['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'defect') ?>

    <?= $form->field($model, 'defect_manufacturer') ?>

    <?= $form->field($model, 'is_warranty_defect')->checkbox(); ?>

    <?= $form->field($model, 'is_repair_possible')->checkbox(); ?>

    <?= $form->field($model, 'is_for_warranty')->checkbox(); ?>

    <?= $form->field($model, 'diagnostic') ?>

    <?= $form->field($model, 'diagnostic_manufacturer') ?>

    <?= $form->field($model, 'repair_status_id')->dropDownList(RepairStatus::repairStatusAsMap(),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'repair_recommendations') ?>

    <?= $form->field($model, 'warranty_status_id')->dropDownList(WarrantyStatus::warrantyStatusAsMap(),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'status_id')->dropDownList(BidStatus::bidStatusAsMap(),['prompt' => 'Выбор']) ?>

    <?= $form->field($model, 'comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Сбросить', ['class' => 'btn btn-default reset-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    $('.reset-form').click(function() {
        var url = location.href.replace(location.search, '');
        var params = new URLSearchParams(location.search.slice(1));
        var title = params.get('title');
        if (title) {
            url += '?title=' + title;
        }
        location.href = url;
    });
JS;

$this->registerJs($script);


