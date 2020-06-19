<?php

use yii\helpers\Html;
use app\models\form\SendReportForm;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model SendReportForm */

$this->title = 'Пересылка отчета';
$this->params['back'] = ['view'];

?>

<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isPhotosSend')->checkbox() ?>

    <?= $form->field($model, 'isActsSend')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
