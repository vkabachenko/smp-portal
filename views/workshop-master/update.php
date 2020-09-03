<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $master \app\models\Master */
/* @var $user \app\models\User */

$this->title = 'Редактирование мастера ' . $user->name;
$this->params['back'] = \Yii::$app->user->can('admin')
    ? ['all-masters', 'workshopId' => $master->workshop_id]
    : ['masters'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?php if (\Yii::$app->user->can('admin')): ?>
            <?= $form->field($master, 'uuid')->textInput(['maxlength' => true]) ?>
        <?php endif; ?>

        <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($master, 'phone')->textInput(['maxlength' => true]) ?>
        <?php if (\Yii::$app->user->can('admin')): ?>
            <?= $form->field($master, 'main')->checkbox() ?>
        <?php endif; ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>