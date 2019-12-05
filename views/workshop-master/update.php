<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $master \app\models\Master */
/* @var $user \app\models\User */

$this->title = 'Редактирование мастера ' . $user->name;
$this->params['back'] = ['masters'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($master, 'phone')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>