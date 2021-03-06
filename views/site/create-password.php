<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/**
 * @var $model \app\models\form\CreatePasswordForm
 * @var $form yii\bootstrap\ActiveForm
 */

$this->title = 'Новый пароль';
$this->params['back'] = ['login'];

?>

<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'passwordAgain')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
