<?php

/* @var $model \app\models\Bid */

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\DecisionWorkshopStatus;
use yii\bootstrap\Html;

?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Изменить решение',
        'class' => 'btn btn-success'
    ]
]) ?>

<?php $form = ActiveForm::begin([
    'id' => 'decision-master-form',
    'action' => [
        'bid/update-decision-master',
        'bidId' => $model->id,
    ]
]) ?>

<?= $form->field($model, 'decision_workshop_status_id')
    ->dropDownList(DecisionWorkshopStatus::decisionWorkshopStatusAsMap(), ['prompt' => 'Выбор']);
?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', [
        'class' => 'btn btn-success'
    ]) ?>
</div>


<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

