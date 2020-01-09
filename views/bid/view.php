<?php

use app\models\Bid;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Просмотр заявки';
$this->params['back'] = ['index'];

$attributes = [
    'created_at',
    'updated_at',
    'brand_name',
    [
        'label' => 'Производитель',
        'value' => $model->manufacturer_id ? $model->manufacturer->name : null,
    ],
    'equipment'
];

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'brand_model_name'])) {
    $attributes[] = 'brand_model_name';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'serial_number'])) {
    $attributes[] = 'serial_number';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'vendor_code'])) {
    $attributes[] = 'vendor_code';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'composition_name'])) {
    $attributes[] = 'composition_name';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'condition_id'])) {
    $attributes[] =     [
        'label' => 'Состояние',
        'value' => $model->condition_id ? $model->condition->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect'])) {
    $attributes[] = 'defect';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'defect_manufacturer'])) {
   $attributes[] = [
        'label' => \Yii::$app->user->can('manager') ? 'Заявленная неисправность' : 'Заявленная неисправность для представительства',
        'value' => $model->defect_manufacturer,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic'])) {
    $attributes[] = 'diagnostic';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'diagnostic_manufacturer'])) {
   $attributes[] = [
        'label' => \Yii::$app->user->can('manager') ? 'Результат диагностики' : 'Результат диагностики для представительства',
        'value' => $model->diagnostic_manufacturer,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_recommendations'])) {
    $attributes[] = 'repair_recommendations';
}

$attributes[] =  [
    'label' => 'Тип клиента',
    'value' => $model->client_type ? Bid::CLIENT_TYPES[$model->client_type] : null,
];

$attributes[] =  'client_name';

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_phone'])) {
    $attributes[] = 'client_phone';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'client_address'])) {
    $attributes[] = 'client_address';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_warranty_defect'])) {
    $attributes[] = [
        'label' => 'Дефект гарантийный',
        'value' => $model->is_warranty_defect ? 'Истина' : 'Ложь',
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_repair_possible'])) {
    $attributes[] =  [
        'label' => 'Проведение ремонта возможно',
        'value' => $model->is_repair_possible ? 'Истина' : 'Ложь',
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'is_for_warranty'])) {
    $attributes[] =  [
        'label' => 'Подано на гарантию',
        'value' => $model->is_for_warranty ? 'Истина' : 'Ложь',
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'treatment_type'])) {
    $attributes[] = [
        'label' => 'Тип обращения',
        'value' => $model->treatmentTypeName,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'saler_name'])) {
    $attributes[] = 'saler_name';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'purchase_date'])) {
    $attributes[] = 'purchase_date:date';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'application_date'])) {
    $attributes[] = 'application_date:date';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_manufacturer'])) {
    $attributes[] = [
        'label' => \Yii::$app->user->can('manager') ? 'Дата принятия в ремонт' : 'Дата принятия в ремонт для представительства',
        'value' => $model->date_manufacturer,
    ];
}


if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'date_completion'])) {
    $attributes[] = 'date_completion:date';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_number'])) {
    $attributes[] = 'warranty_number';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_number'])) {
    $attributes[] = 'bid_number';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_manufacturer_number'])) {
    $attributes[] = [
        'label' => \Yii::$app->user->can('manager') ? 'Номер заявки' : 'Номер заявки у представительства',
        'value' => $model->bid_manufacturer_number,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'bid_1C_number'])) {
    $attributes[] = 'bid_1C_number';
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'repair_status_id'])) {
    $attributes[] =  [
        'label' => 'Статус ремонта',
        'value' => $model->repair_status_id ? $model->repairStatus->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'warranty_status_id'])) {
    $attributes[] =  [
        'label' => 'Статус гарантии',
        'value' => $model->warranty_status_id ? $model->warrantyStatus->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'status_id'])) {
    $attributes[] =  [
        'label' => 'Статус заявки',
        'value' => $model->status_id ? $model->status->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'user_id'])) {
    $attributes[] =  [
        'label' => 'Приемщик',
        'value' => $model->user_id ? $model->user->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'master_id'])) {
    $attributes[] =      [
        'label' => 'Мастер',
        'value' => $model->master_id ? $model->master->user->name : null,
    ];
}

if (\Yii::$app->user->can('adminBidAttribute', ['attribute' => 'comment'])) {
    $attributes[] =  'comment';
}

?>
<div>

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-sm-3 col-xs-6">
            <?= Html::a('История', ['bid-history/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
        </div>

        <?php if (\Yii::$app->user->can('updateBid', ['bidId' => $model->id])): ?>
            <div class="col-sm-3  col-xs-6">
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('updateBidStatus', ['bidId' => $model->id])): ?>
            <div class="col-sm-3  col-xs-6">
                <?= Html::a('Статус', ['update-status', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('sendAct', ['bidId' => $model->id])): ?>
            <div class="col-sm-3  col-xs-6 bid-view-send">
                <?= Html::a('Отправить', ['send-act/index', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
]);
?>

<div class="form-group">
    <?= Html::a('Комментарии', ['bid-comment/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
</div>

<?php if (!empty($model->bidImages)): ?>
    <?= $this->render('_images', ['model' => $model]) ?>
<?php endif; ?>

<div class="form-group clearfix"></div>
<div>
    <?= Html::a('Добавить фото', ['bid-image/create', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
</div>

