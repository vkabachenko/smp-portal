<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this \yii\web\View */
/* @var $agency \app\models\Agency */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Загрузка шаблона Excel';
$this->params['back'] = ['agency-template/index', 'agencyId' => $agency->id];
?>

<div>
    <?php $form = ActiveForm::begin(); ?>

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
