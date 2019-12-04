<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $manager \app\models\Manager */
/* @var $user \app\models\User */

$this->title = 'Редактирование менеджера ' . $user->name;
$this->params['back'] = ['managers'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($manager, 'phone')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>