<?php

use app\helpers\constants\Constants;
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

<?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'created_at'])): ?>
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
<?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'manufacturer_id'])): ?>
        <?= $form->field($model, 'manufacturer_id')->dropDownList(Manufacturer::manufacturersAsMap(true),['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_name'])): ?>
        <?= $form->field($model, 'brand_name') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'equipment'])): ?>
        <?= $form->field($model, 'equipment') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_model_name'])): ?>
        <?= $form->field($model, 'brand_model_name') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'composition_name'])): ?>
        <?= $form->field($model, 'composition_name') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'serial_number'])): ?>
        <?= $form->field($model, 'serial_number') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])): ?>
        <?= $form->field($model, 'vendor_code') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])): ?>
        <?= $form->field($model, 'master_id')->dropDownList(Master::mastersAsMap(\Yii::$app->user->identity, true),['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_id'])): ?>
        <?= $form->field($model, 'client_type')->dropDownList(\app\models\Client::CLIENT_TYPES, ['prompt' => 'Выбор']) ?>
        <?= $form->field($model, 'client_name') ?>
        <?= $form->field($model, 'client_phone') ?>
        <?= $form->field($model, 'client_email') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_manufacturer_id'])): ?>
        <?= $form->field($model, 'client_manufacturer_type')->dropDownList(\app\models\Client::CLIENT_TYPES, ['prompt' => 'Выбор']) ?>
        <?= $form->field($model, 'client_manufacturer_name') ?>
        <?= $form->field($model, 'client_manufacturer_phone') ?>
        <?= $form->field($model, 'client_manufacturer_email') ?>
    <?php endif; ?>


    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])): ?>
        <?= $form->field($model, 'treatment_type')->dropDownList(Constants::EMPTY_ELEMENT + Bid::TREATMENT_TYPES ,['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'saler_name'])): ?>
        <?= $form->field($model, 'saler_name') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'purchase_date'])): ?>
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
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'application_date'])): ?>
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
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_manufacturer'])): ?>
        <div class="form-group">
            <label class="control-label">
                <?= \Yii::$app->user->can('manager') ? 'Дата принятия в ремонт' : 'Дата принятия в ремонт для представительства' ?>
            </label>
            <?= DatePicker::widget([
                'model' => $model,
                'attribute' => 'date_manufacturer_from',
                'attribute2' => 'date_manufacturer_to',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
            ]) ?>
        </div>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion'])): ?>
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
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_number'])): ?>
        <?= $form->field($model, 'warranty_number') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_number'])): ?>
        <?= $form->field($model, 'bid_number') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_1C_number'])): ?>
        <?= $form->field($model, 'bid_1C_number') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_manufacturer_number'])): ?>
        <?= $form->field($model, 'bid_manufacturer_number')->label(
            \Yii::$app->user->can('manager') ? 'Номер заявки' : 'Номер заявки у представительства'
        ) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_name'])): ?>
        <?= $form->field($model, 'condition_name')->label('Состояние') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect'])): ?>
        <?= $form->field($model, 'defect') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect_manufacturer'])): ?>
        <?= $form->field($model, 'defect_manufacturer')->label(
            \Yii::$app->user->can('manager') ? 'Заявленная неисправность' : 'Заявленная неисправность для представительства'
        ) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty_defect'])): ?>
        <?= $form->field($model, 'is_warranty_defect')->checkbox(); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_repair_possible'])): ?>
        <?= $form->field($model, 'is_repair_possible')->checkbox(); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_for_warranty'])): ?>
        <?= $form->field($model, 'is_for_warranty')->checkbox(); ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic'])): ?>
        <?= $form->field($model, 'diagnostic') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic_manufacturer'])): ?>
        <?= $form->field($model, 'diagnostic_manufacturer')->label(
            \Yii::$app->user->can('manager') ? 'Результат диагностики' : 'Результат диагностики для представительства'
        ) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])): ?>
        <?= $form->field($model, 'repair_status_id')->dropDownList(RepairStatus::repairStatusAsMap(true),['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_recommendations'])): ?>
        <?= $form->field($model, 'repair_recommendations') ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])): ?>
        <?= $form->field($model, 'warranty_status_id')->dropDownList(WarrantyStatus::warrantyStatusAsMap(true),['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'status_id'])): ?>
        <?= $form->field($model, 'status_id')->dropDownList(BidStatus::bidStatusAsMap(true),['prompt' => 'Выбор']) ?>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment'])): ?>
        <?= $form->field($model, 'comment') ?>
    <?php endif; ?>

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


