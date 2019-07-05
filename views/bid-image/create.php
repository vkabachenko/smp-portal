<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>
    <div>
        <?= $form->field($uploadForm, 'files[]')->widget(FileInput::class, [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions'=>['allowedFileExtensions'=>['jpg','jpeg','png'],'showUpload' => false,]
                ])
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

