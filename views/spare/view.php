<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Spare */

$this->title = $model->name;
$this->params['back'] = ['index', 'bidId' => $model->bid_id];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\Yii::$app->user->can('manageSpare', ['bidId' => $model->bid_id])): ?>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить запчасть?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vendor_code',
            'name',
            'quantity',
            'price',
            'total_price',
            'invoice_number',
            'invoice_date',
            'description:ntext',
        ],
    ]) ?>

</div>
