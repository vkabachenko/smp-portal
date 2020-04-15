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

    <?= $this->render('partial/_form', ['form' => $form, 'model' => $model]) ?>

    <div>
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($rules, 'paidBid')->checkbox() ?>
        </div>
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($rules, 'exchange1C')->checkbox() ?>
        </div>

<?php if (!$model->isNewRecord): ?>
        <div class="col-xs-6 col-sm-3">

            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Настройка полей заявки
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li>
                        <?= Html::a('Скрыть/показать',
                            ['workshop/bid-attributes', 'workshopId' => $model->id],
                            ['class' => 'btn btn-default'])
                        ?>
                    </li>
                    <li>
                        <?= Html::a('Порядок расположения полей',
                            ['workshop/bid-attributes-sections', 'workshopId' => $model->id],
                            ['class' => 'btn btn-default'])
                        ?>
                    </li>
                    <li>
                        <?= Html::a('Настройка обмена с 1С',
                            ['workshop/bid-attributes-exchange', 'workshopId' => $model->id],
                            ['class' => 'btn btn-default'])
                        ?>
                    </li>
                </ul>
            </div>
        </div>
<?php endif; ?>

    </div>

    <div class="clearfix "></div>

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

