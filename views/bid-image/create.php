<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vkabachenko\filepond\widget\FilepondWidget;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= Html::label('Загрузить фотографии', null, ['class' => 'control-label']) ?>
        <?= FilepondWidget::widget([
            'filepondClass' => 'load-bid-images',
            'model' => $uploadForm,
            'attribute' => 'files[]',
            'multiple' => true,
            'instanceOptions' => [
                'allowFileTypeValidation' => true,
                'acceptedFileTypes' => ['image/*']
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

