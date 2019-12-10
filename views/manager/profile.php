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
    <?php endif ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>