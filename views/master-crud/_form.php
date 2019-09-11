<?php

use app\models\Workshop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $master app\models\Master */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workshop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($master, 'workshop_id')
        ->dropDownList(Workshop::workshopsAsMap(),['prompt' => 'Выбор']); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


