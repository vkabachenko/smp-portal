<?php

/* @var $this yii\web\View */
/* @var $agencyWorkshop \app\models\AgencyWorkshop */
/* @var $uploadImageForm \app\models\form\UploadImageForm */

use kartik\file\FileInput;
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Реквизиты договора';
$this->params['back'] = ['agencies', 'workshopId' => $agencyWorkshop->workshop_id];

?>

<div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($agencyWorkshop, 'contract_nom'); ?>

    <?= $form->field($agencyWorkshop, 'contract_date')
        ->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>

    <div class="form-group">
        <?= $form->field($uploadImageForm, 'file')->widget(FileInput::class, [
            'options' => ['accept' => 'image/*'],
            'pluginOptions'=>['showUpload' => false,]
        ])->label('Скан договора');
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
