<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/**
 * @var $model \app\models\form\ResetPasswordRequestForm
 * @var $form yii\bootstrap\ActiveForm
 */

$this->title = 'Восстановление пароля';
$this->params['back'] = ['login'];

?>

<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email') ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
