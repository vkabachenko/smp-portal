<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BidStatus;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */

$this->title = 'Редактирование статуса заявки';
$this->params['back'] = ['view', 'id' => $model->id];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status_id')
            ->dropDownList(BidStatus::bidStatusAsMap(),['prompt' => 'Выбор']); ?>

        <?php if (\Yii::$app->user->can('master')): ?>
            <?= $form->field($model, 'decision_workshop_status_id')
                ->dropDownList(\app\models\DecisionWorkshopStatus::decisionWorkshopStatusAsMap(),['prompt' => 'Выбор']); ?>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('manager')): ?>
            <?= $form->field($model, 'decision_agency_status_id')
                ->dropDownList(\app\models\DecisionAgencyStatus::decisionAgencyStatusAsMap(),['prompt' => 'Выбор']); ?>
        <?php endif; ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

