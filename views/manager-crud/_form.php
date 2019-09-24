<?php

use app\models\Manufacturer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $manager app\models\Manager */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($manager, 'manufacturer_id')
        ->dropDownList(Manufacturer::manufacturersAsMap(),['prompt' => 'Выбор']); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


