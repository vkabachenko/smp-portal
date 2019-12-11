<?php

/* @var $this yii\web\View */
/* @var $agency app\models\Agency */
/* @var $form yii\widgets\ActiveForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Manufacturer;

$this->title = 'Заполните данные представительства';

?>

<div>

    <h2><?= $this->title ?></h2>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($agency, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'description')->textarea() ?>
        <?= $form->field($agency, 'manufacturer_id')
            ->dropDownList(Manufacturer::manufacturersAsMap(),[
                'prompt' => 'Выбор',
            ]); ?>
        <?= $form->field($agency, 'phone1')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone2')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone3')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'phone4')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email1')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email2')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email3')->textInput(['maxlength' => true]) ?>
        <?= $form->field($agency, 'email4')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>