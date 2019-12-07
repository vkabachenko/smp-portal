<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Workshop */
/* @var $form yii\widgets\ActiveForm */
/* @var $rules app\models\form\WorkshopRulesForm */
?>

<div class="workshop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div>
        <div class="col-md-9 col-sm-6 col-xs-12" style="padding-left: 0;">
            <?= $form->field($model, 'token')->textInput(['maxlength' => true, 'id' => 'workshop-token']) ?>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-top: 20px;">
            <button id="workshop-token-btn" class="btn btn-success">
                Сгенерировать
            </button>
        </div>
    </div>
    <div class="clearfix"></div>

    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($rules, 'paidBid')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $script = <<<JS
        $(function(){
           $('#workshop-token-btn').click(function(evt) {
               evt.preventDefault();
               var token = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                $('#workshop-token').val(token); 
           }); 
        });
JS;
    $this->registerJs($script);

