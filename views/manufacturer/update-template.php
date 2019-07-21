<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturer */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */

$this->title = 'Заменить шаблон Excel производителя: ' . $model->name;
$this->params['back'] = ['index-template'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div>

        <?php $form = ActiveForm::begin(); ?>

        <?php if ($model->act_template): ?>
            <div class="form-group">
                <?= \yii\bootstrap\Html::a($model->act_template, ['download/act-excel', 'filename' => $model->act_template]) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
                'pluginOptions'=>['allowedFileExtensions'=>['xlsx'],'showUpload' => false,]
            ])
            ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['name' => 'btn-save', 'class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
