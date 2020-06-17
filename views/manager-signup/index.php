<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\Manufacturer;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model \app\models\form\SignupManagerForm */

$this->title = 'Регистрация менеджера';

?>

<div>
    <h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'userName') ?>
        <?= $form->field($model, 'email')->textInput(['disabled' => true])  ?>
        <?= $form->field($model, 'phone') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>