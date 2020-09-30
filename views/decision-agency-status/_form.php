<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TemplateModel;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \app\models\DecisionAgencyStatus */

?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sub_type_act')->dropDownList(TemplateModel::SUB_TYPE_ACTS, ['prompt' => 'Выбор']) ?>
    <?= $form->field($model, 'email_subject')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email_body')->textarea() ?>
    <?= $form->field($model, 'email_signature')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
