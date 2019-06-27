<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BidHistory;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\BidHistory */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
?>

<div class="bid-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')
        ->dropDownList(BidHistory::STATUSES,[
            'prompt' => 'Выбор',
            'class' => 'form-control bid-history-status',
        ]); ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

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

