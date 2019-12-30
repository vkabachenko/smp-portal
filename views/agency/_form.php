<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'manufacturer_id')
        ->dropDownList(Manufacturer::manufacturersAsMap(),[
            'prompt' => 'Выбор',
            'disabled' => !$model->isNewRecord
        ]); ?>
    <?= $form->field($model, 'phone1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone4')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email4')->textInput(['maxlength' => true]) ?>

    <div>
        <div class="form-group col-xs-6">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="form-group col-xs-6">
            <?php if (!$model->isNewRecord): ?>
                <?= Html::a('Поля заявки',
                    ['agency/bid-attributes', 'agencyId' => $model->id],
                    ['class' => 'btn btn-primary'])
                ?>
            <?php endif; ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>


