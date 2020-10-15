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

    <?= $form->errorSummary($model); ?>

        <div class="form-group hidden">
            <?= Html::a('', '#', ['class' => 'btn btn-primary toggle-extended hidden-extended']) ?>
        </div>

        <?php if (\Yii::$app->user->can('master')): ?>
        <?php if (count($model->images) > 0): ?>
            <?= $form->field($model, 'images')
                ->checkboxList($model->images,[
                    'encode' => false,
                    'separator'=>'<br/>',
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        $checked = $model->sent[$index] ? '' : 'checked';
                        $sent = $model->sent[$index] ? 'Уже отправлено' : '';
                        $fileName = $model->label[$index];
                        return "<label>
                            <input 
                                type='checkbox' 
                                {$checked} 
                                name='{$name}' 
                                value='{$value}'>&nbsp;{$label}&nbsp;{$fileName}&nbsp;{$sent}
                                </label>";
                    }
                ])
            ?>
        <?php endif; ?>
        <?php endif; ?>

        <div class="extended-form email-field">
            <?= $form->field($model, 'email') ?>
        </div>

    <?php if ($model->act && $model->act->isGenerated()): ?>
        <div class="form-group extended-form">
            <?= Html::a('Акт технического состояния', [
                    'download/act-excel',
                    'filename' => $model->act->getFilename(),
                    'directory' => $model->act->getDirectory()
            ]) ?>
        </div>
    <?php endif; ?>

    <div class="form-group  extended-form">
        <?= $form->field($uploadForm, 'file')->widget(FileInput::class, [
            'pluginOptions'=>['allowedFileExtensions'=>['xlsx'],'showUpload' => false,]
        ])
            ->label($model->act && $model->act->isGenerated() ?
            'Заменить сгенерированный акт'  :
            'Акт технического состояния'
            )
        ?>
    </div>

    <div class="form-group">
        <div class="col-lg-4">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-lg-4  extended-form">
            <?= Html::a('Сгенерировать акт', ['generate', 'bidId' => $model->bidId], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {
    $('.toggle-extended').click(function(evt) {
        evt.preventDefault();
        
        var self = $(this);
        if (self.hasClass('hidden-extended')) {
            self.text('Показать расширенные настройки');
            self.removeClass('hidden-extended');
        } else {
            self.text('Скрыть расширенные настройки');
            self.addClass('hidden-extended');
        }
        $('.extended-form').toggle();
    });
    $('.toggle-extended').trigger('click');
});

JS;

$this->registerJs($script);
