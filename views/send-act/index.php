<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\form\SendActForm */

$this->title = 'Отправка акта на email';
$this->params['back'] = ['bid/view', 'id' => $model->bidId];
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'images')
            ->checkboxList($model->images,['encode' => false, 'separator'=>'<br/>']) ?>
        <?= $form->field($model, 'email') ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
