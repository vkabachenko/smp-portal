<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\Manufacturer;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model \app\models\form\SignupAgencyForm */

$this->title = 'Регистрация производителя';
$this->params['back'] = ['site/index'];
?>

<div>
    <h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'agencyName')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'manufacturerId')
            ->dropDownList(Manufacturer::manufacturersAsMap(),[
                'prompt' => 'Выбор',
            ]); ?>

        <?= $form->field($model, 'userName') ?>
        <?= $form->field($model, 'email') ?>

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