<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TemplateModel;
use app\models\Bid;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \app\models\DecisionAgencyStatus */
/* @var $autoFilledAttributes array */

?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sub_type_act')->dropDownList(TemplateModel::SUB_TYPE_ACTS, ['prompt' => 'Выбор']) ?>
    <?= $form->field($model, 'email_subject')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email_body')->textarea() ?>
    <?= $form->field($model, 'email_signature')->textInput(['maxlength' => true]) ?>

    <div>
        <h3>Поля заявки для автоматического заполнения</h3>
        <?php foreach ($model->auto_fill as $attribute => $value): ?>
            <div class="form-group">
                <?php $id = 'DecisionAgencyStatus-auto_fill-' . $attribute ?>

                <?= Html::label(Bid::getAllAttributes()[$attribute],
                    $id,
                    ['class' => 'control-label']
                ) ?>

                <?php $name = 'DecisionAgencyStatus[auto_fill][' . $attribute . ']'; ?>
                <?php if (is_array($autoFilledAttributes[$attribute])): ?>
                    <?= Html::dropDownList($name, $value, $autoFilledAttributes[$attribute]['options'],
                        ['prompt' => '', 'class' => 'form-control', 'id' => $id])
                    ?>
                <?php else: ?>
                    <?= Html::textInput($name, $value, ['class' => 'form-control', 'id' => $id]) ?>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
