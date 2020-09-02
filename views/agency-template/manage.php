<?php
/* @var $this \yii\web\View */
/* @var $template \app\models\TemplateModel */
/* @var $uploadForm \app\models\form\UploadExcelTemplateForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\TemplateModel;
use kartik\file\FileInput;

$this->title = $template->getHeader();
$this->params['back'] = ['agency-template/index'];
?>

<h2><?= $this->title ?></h2>

<div>

    <div class="form-group">
        <?php if ($template->type == TemplateModel::TYPE_ACT): ?>
            <?= Html::a('Образец шаблона',
                [
                    'download/default',
                    'filename' => 'template_act_sample.xlsx',
                    'path' => \Yii::getAlias('@app/templates/excel/act/sample.xlsx')
                ]);
            ?>
        <?php elseif ($template->type == TemplateModel::TYPE_REPORT): ?>
            <?= Html::a('Образец шаблона',
                [
                    'download/default',
                    'filename' => 'template_report_sample.xlsx',
                    'path' => \Yii::getAlias('@app/templates/excel/report/sample.xlsx')
                ]);
            ?>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?php if ($template->file_name): ?>
            <?= Html::a('Загруженный шаблон',
                ['download/agency-template', 'templateId' => $template->id]);
            ?>
        <?php endif; ?>

    </div>


    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
                'pluginOptions'=>['allowedFileExtensions'=>['xlsx'],'showUpload' => false,]
            ])
            ?>
        </div>

        <?= $form->field($template, 'email_subject')->textInput(['maxlength' => true]) ?>
        <?= $form->field($template, 'email_body')->textarea() ?>
        <?= $form->field($template, 'email_signature')->textInput(['maxlength' => true]) ?>

        <div class="form-group row">
            <div class="col-xs-6 col-sm-3">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="col-xs-6 col-sm-3">
                <?php if (!$template->isNewRecord): ?>
                    <?= Html::a('Удалить', ['agency-template/delete', 'id' => $template->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            </div>
        </div>


    <?php ActiveForm::end(); ?>



</div>
