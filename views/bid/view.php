<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Просмотр заявки';
$this->params['back'] = ['index'];
?>
<div class="bid-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-sm-4 col-xs-6">
            <?= Html::a('История', ['bid-history/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-sm-4  col-xs-6">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-sm-4  col-xs-6 bid-view-send">
            <?= Html::a('Отправить', ['send-act/index', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'created_at',
        'brand_name',
        [
            'label' => 'Производитель',
            'value' => $model->manufacturer_id ? $model->manufacturer->name : null,
        ],
        'equipment',
        'brand_model_name',
        'serial_number',
        'vendor_code',
        'composition_name',
        [
            'label' => 'Состояние',
            'value' => $model->condition_id ? $model->condition->name : null,
        ],
        'defect',
        'diagnostic',
        'client_name',
        'client_phone',
        'client_address',
        'treatmentTypeName',
        'purchase_date:date',
        'application_date:date',
        'warranty_number',
        'bid_number',
        'bid_1C_number',
        'bid_manufacturer_number',
        [
            'label' => 'Статус ремонта',
            'value' => $model->repair_status_id ? $model->repairStatus->name : null,
        ],
        [
            'label' => 'Статус гарантии',
            'value' => $model->warranty_status_id ? $model->warrantyStatus->name : null,
        ],
        [
            'label' => 'Статус заявки',
            'value' => $model->status_id ? $model->status->name : null,
        ],
        [
            'label' => 'Мастер',
            'value' => $model->user_id ? $model->user->name : null,
        ],
    ]

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

