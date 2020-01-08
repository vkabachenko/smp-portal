<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('partial/_form', compact('form','model')) ?>

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


