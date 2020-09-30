<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DecisionWorkshopStatus;
use app\models\DecisionAgencyStatus;
use app\models\BidStatus;
use app\models\Bid;

$autoFilledAttributes = Bid::autoFilledAttributes();

/* @var $this yii\web\View */
/* @var $model app\models\AutoFillAttributes */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'decision_workshop_status_id')
        ->dropDownList(DecisionWorkshopStatus::decisionWorkshopStatusAsMap(), ['prompt' => '', 'disabled' => !$model->isNewRecord])
    ?>

    <?= $form->field($model, 'decision_agency_status_id')
        ->dropDownList(DecisionAgencyStatus::decisionAgencyStatusAsMap(), ['prompt' => '', 'disabled' => !$model->isNewRecord])
    ?>

    <?= $form->field($model, 'status_id')
        ->dropDownList(BidStatus::bidStatusAsMap(), ['prompt' => '', 'disabled' => !$model->isNewRecord])
    ?>

    <div>
        <h3>Поля заявки для автоматического заполнения</h3>
        <?php foreach ($model->auto_fill as $attribute => $value): ?>
            <div class="form-group">
                <?php $id = 'AutoFillAttributes-auto_fill-' . $attribute ?>

                <?= Html::label(Bid::getAllAttributes()[$attribute],
                    $id,
                    ['class' => 'control-label']
                ) ?>

                <?php $name = 'AutoFillAttributes[auto_fill][' . $attribute . ']'; ?>
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
