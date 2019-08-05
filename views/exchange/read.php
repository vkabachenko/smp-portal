<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\UploadXmlForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>
    <div>
        <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
                'pluginOptions'=>['allowedFileExtensions'=>['xml'],'showUpload' => false,]
                ])
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

