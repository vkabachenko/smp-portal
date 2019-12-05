<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $master app\models\Master */
/* @var $workshop app\models\Workshop */
/* @var $form yii\widgets\ActiveForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Manufacturer;

$this->title = 'Профиль мастера ' . $user->name;
$this->params['back'] = ['master/index'];

?>

<div>

    <h2><?= $this->title ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'email')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    <?= $form->field($master, 'phone')->textInput(['maxlength' => true]) ?>

    <?php if (\Yii::$app->user->can('manageWorkshops')): ?>
        <?= $form->field($workshop, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($workshop, 'description')->textarea() ?>
    <?php endif ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>