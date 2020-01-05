<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $manager app\models\Manager */
/* @var $agency app\models\Agency */
/* @var $form yii\widgets\ActiveForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Manufacturer;

$this->title = 'Профиль менеджера ' . $user->name;
$this->params['back'] = ['manager/index'];

?>

<div>

    <h2><?= $this->title ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'email')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    <?= $form->field($manager, 'phone')->textInput(['maxlength' => true]) ?>

    <?php if (\Yii::$app->user->can('updateAgency')): ?>
        <?= $form->field($agency, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'description')->textarea() ?>
        <?= $form->field($agency, 'manufacturer_id')
            ->dropDownList(Manufacturer::manufacturersAsMap(),[
                'prompt' => 'Выбор',
            ]); ?>
        <?= $form->field($agency, 'phone1',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 1 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone2',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 2 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone3',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 3 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone4',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'phone 4 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email1',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 1 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email2',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 2 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email3',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 3 title']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email4',
            ['labelOptions' => ['class' => 'column-hint', 'data-title' => 'email 4 title']])
            ->textInput(['maxlength' => true]) ?>
    <?php endif ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>