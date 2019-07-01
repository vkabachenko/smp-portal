<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Просмотр заявки';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-lg-6">
            <?= Html::a('История', ['bid-history/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-lg-6">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'created_at',
        'brand_name',
        [
            'label' => 'Производитель',
            'value' => $model->manufacturer->name,
        ],
        'equipment',
        'brand_model_name',
        'serial_number',
        'vendor_code',
        'composition_name',
        [
            'label' => 'Состояние',
            'value' => $model->condition->name,
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
        'bid_manufacturer_number'
    ]

]);
?>

</div>
