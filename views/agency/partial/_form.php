<?php

use app\models\Bid;

/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */

$manufacturer = $model->isNewRecord ? false : Bid::find()->where(['agency_id' => $model->id])->exists();

use app\models\Manufacturer;

?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'manufacturer_id')
    ->dropDownList(Manufacturer::manufacturersAsMap(),[
        'prompt' => 'Выбор',
        'disabled' => $manufacturer
    ]); ?>
<?= $form->field($model, 'phone1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone1']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone3']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone4']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'is_independent')->checkbox() ?>
<?= $form->field($model, 'email2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email3']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email4']]])
    ->textInput(['maxlength' => true]) ?>

