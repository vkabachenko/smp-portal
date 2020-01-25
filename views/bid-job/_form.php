<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BidJob */
/* @var $form yii\widgets\ActiveForm */
/* @var $bid app\models\Bid */
/* @var $jobsCatalog \app\models\JobsCatalog */
/* @var $jobsCatalogService \app\services\job\JobsCatalogService */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jobs_catalog_id')
        ->dropDownList($jobsCatalogService->jobsCatalogAsMap()) ?>

    <?= $form->field($jobsCatalog, 'vendor_code')->textInput(['disabled' => true]) ?>
    <?= $form->field($jobsCatalog, 'jobsSectionName')->textInput(['disabled' => true]) ?>
    <?= $form->field($jobsCatalog, 'hour_tariff')->textInput(['disabled' => true]) ?>
    <?= $form->field($jobsCatalog, 'hours_required')->textInput(['disabled' => true]) ?>
    <?= $form->field($jobsCatalog, 'price')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
