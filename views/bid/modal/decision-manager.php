<?php

/* @var $model \app\models\Bid */

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\DecisionAgencyStatus;
use yii\bootstrap\Html;

?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Изменить решение',
        'class' => 'btn btn-success'
    ]
]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'decision-manager-form',
        'action' => [
            'bid/update-decision-manager',
            'bidId' => $model->id,
        ]
    ]) ?>

        <?= $form->field($model, 'decision_agency_status_id')
            ->dropDownList(DecisionAgencyStatus::decisionAgencyStatusAsMap(), ['prompt' => 'Выбор']);
         ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success'
            ]) ?>
        </div>


    <?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

