<?php

use kartik\file\FileInput;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturer */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if ($model->act_template): ?>
        <div class="form-group">
            <?= Html::a($model->act_template, ['download/act-excel', 'filename' => $model->act_template]) ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
            'pluginOptions'=>['allowedFileExtensions'=>['xlsx'],'showUpload' => false,]
        ])
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
