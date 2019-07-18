<?php

use kartik\file\FileInput;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\form\SendActForm */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */

$this->title = 'Отправка акта на email';
$this->params['back'] = ['bid/view', 'id' => $model->bidId];
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'images')
            ->checkboxList($model->images,['encode' => false, 'separator'=>'<br/>']) ?>
        <?= $form->field($model, 'email') ?>

    <?php if ($model->act->isGenerated()): ?>
        <div class="form-group">
            <?= Html::a('Акт технического состояния', [
                    'download/act-excel',
                    'filename' => $model->act->getFilename(),
                    'directory' => $model->act->getDirectory()
            ]) ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
            'pluginOptions'=>['allowedFileExtensions'=>['xlsx'],'showUpload' => false,]
        ])
            ->label($model->act->isGenerated() ?
            'Заменить сгенерированный акт'  :
            'Акт технического состояния'
            )
        ?>
    </div>

    <div class="form-group">
        <div class="col-lg-4">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-lg-4">
            <?= Html::a('Сгенерировать акт', ['generate', 'bidId' => $model->bidId], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>