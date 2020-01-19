<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BidJob */
/* @var $form yii\widgets\ActiveForm */
/* @var $bid app\models\Bid */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jobs_catalog_id')
        ->dropDownList(\app\models\JobsCatalog::jobsCatalogAsMap($bid->agency_id)) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
