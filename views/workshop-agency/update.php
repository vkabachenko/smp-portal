<?php

/* @var $this yii\web\View */
/* @var $workshopId int */
/* @var $agencyId int */
/* @var $uploadImageForm \app\models\form\UploadImageForm */

use kartik\file\FileInput;
use yii\bootstrap\Html;
use kartik\form\ActiveForm;

$this->title = 'Прикрепить скан документа';
$this->params['back'] = ['agencies', 'workshopId' => $workshopId];

?>

<div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($uploadImageForm, 'file')->widget(FileInput::class, [
            'options' => ['accept' => 'image/*'],
            'pluginOptions'=>['showUpload' => false,]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
