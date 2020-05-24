<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $report app\models\Report */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Редактировать отчет';
$this->params['back'] = ['index'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($report, 'report_nom') ?>

        <?= $form->field($report, 'report_date')
            ->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => ['class' => 'form-control']
            ]) ?>

        <div class="form-group">
            <label class="control-label" for="report-change">Изменить файл отчета</label>
            <?= Html::dropDownList(
                'fileMode',
                null,
                [1 => 'Сгенерировать', 2 => 'Загрузить'],
                ['id' => 'report-change', 'class' => 'form-control', 'prompt' => ''])
            ?>
        </div>

        <div class="form-group report-upload" style="display: none;">
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

</div>

<?php
$script = <<<JS
$(function() {
    $('#report-change').change(function() {
        if ($(this).val() == 2) {
            $('.report-upload').show();
        } else {
            $('.report-upload').hide();
        }
    })    
})
JS;

$this->registerJs($script);

