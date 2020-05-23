<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $report app\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($report, 'selectedBids')
        ->checkboxList($report->selectedBids,[
            'encode' => false,
            'separator'=>'<br/>',
            'item' => function ($index, $label, $name, $checked, $value) {
                $url = Url::to(['bid/view', 'id' => $value]);
                $checked = 'checked';
                return "<label><input type='checkbox' {$checked} name='{$name}' value='{$value}'>
                        <a href='{$url}' target='_blank'>{$label}</a>
                    </label>";
            }
        ])
    ?>

    <?= $form->field($report, 'report_nom') ?>

    <?= $form->field($report, 'report_date')
        ->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



