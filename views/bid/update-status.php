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

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

