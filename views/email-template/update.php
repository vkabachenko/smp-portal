<?php

use app\models\form\EmailTemplateForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\form\EmailTemplateForm */

$this->title = 'Шаблон письма';
$this->params['back'] = ['manager/index'];
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textarea() ?>
    <?= $form->field($model, 'signature') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
