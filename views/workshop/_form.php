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

    <?= $this->render('partial/_form', ['form' => $form, 'model' => $model]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



