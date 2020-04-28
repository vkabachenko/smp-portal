<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\jui\DatePicker as JuiDatePicker;

/* @var $this yii\web\View */
/* @var $reportForm \app\models\form\ReportForm*/
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Отчет по выполненным заявкам';
$this->params['back'] = ['bid/index'];
?>

<h2><?= Html::encode($this->title) ?></h2>

<div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label class="control-label">Диапазон дат заявок</label>
        <?= DatePicker::widget([
            'model' => $reportForm,
            'attribute' => 'dateFrom',
            'attribute2' => 'dateTo',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => ['autoclose' => true, 'format' => 'dd.mm.yyyy']
        ]) ?>
    </div>

    <?= $form->field($reportForm, 'reportNom'); ?>

    <?= $form->field($reportForm, 'reportDate')
        ->widget(JuiDatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>




