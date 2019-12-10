<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\form\InviteMasterForm */

$this->title = 'Приглашение мастера';
$this->params['back'] = ['masters'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>