<?php

/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */

use app\models\Manufacturer;

?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'manufacturer_id')
    ->dropDownList(Manufacturer::manufacturersAsMap(),[
        'prompt' => 'Выбор',
        'disabled' => !$model->isNewRecord
    ]); ?>
<?= $form->field($model, 'phone1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 1 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 2 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 3 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 4 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 1 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 2 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 3 title']])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 4 title']])
    ->textInput(['maxlength' => true]) ?>

