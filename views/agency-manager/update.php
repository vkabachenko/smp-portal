<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $manager \app\models\Manager */
/* @var $user \app\models\User */

$this->title = 'Редактирование менеджера ' . $user->name;
$this->params['back'] = \Yii::$app->user->can('admin')
    ? ['all-managers', 'agencyId' => $manager->agency_id]
    : ['managers'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?php if (\Yii::$app->user->can('admin')): ?>
        <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($manager, 'main')->checkbox() ?>
    <?php endif; ?>
        <?= $form->field($manager, 'phone')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>