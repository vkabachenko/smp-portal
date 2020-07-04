<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Client */

use yii\bootstrap\Modal;
use app\models\Client;
use app\helpers\common\DateHelper;

$attributes = [];

if (!empty($model->name)) {
    $attributes[] = 'name';
}

if (!empty($model->full_name)) {
    $attributes[] = 'full_name';
}

$attributes[] = [
    'label' => $model->attributeLabels()['client_type'],
    'value' => Client::CLIENT_TYPES[$model->client_type]
];

if (!empty($model->date_register)) {
    $attributes[] = [
        'label' => $model->attributeLabels()['date_register'],
        'value' => DateHelper::getReadableDate($model->date_register)
    ];
}

foreach ($model->clientPhones as $index => $clientPhone) {
    $attributes[] = [
        'label' => 'Телефон ' . (string)($index + 1),
        'value' => $clientPhone->phone
    ];
}

if (!empty($model->email)) {
    $attributes[] = 'email';
}

if (!empty($model->inn)) {
    $attributes[] = 'inn';
}


if (!empty($model->kpp)) {
    $attributes[] = 'kpp';
}


if (!empty($model->address_actual)) {
    $attributes[] = 'address_actual';
}

if (!empty($model->address_legal)) {
    $attributes[] = 'address_legal';
}

if (!empty($model->comment)) {
    $attributes[] = 'comment:ntext';
}

if (!empty($model->description)) {
    $attributes[] = 'description:ntext';
}

?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Дополнительно...',
    ]
]) ?>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
]) ?>

<?php Modal::end(); ?>


