<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
        ->dropDownList($jobsCatalogService->jobsCatalogAsMap(), [
            'id' => 'jobs-catalog-select',
            'data-url' => Url::to(['change-jobs-catalog'])
        ])
    ?>

    <?= $form->field($jobsCatalog, 'vendor_code')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-vendor-code'
    ])
    ?>
    <?= $form->field($jobsCatalog, 'jobsSectionName')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-vendor-section-name'
    ])
    ?>
    <?= $form->field($jobsCatalog, 'hour_tariff')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-hour-tariff'
    ])
    ?>
    <?= $form->field($jobsCatalog, 'hours_required')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-hours-required'
    ])
    ?>
    <?= $form->field($jobsCatalog, 'price')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-price'
    ])
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {
    $('#jobs-catalog-select').change(function() {
        var self = $(this);
        $.ajax({
            url: self.data('url'),
            method: 'POST',
            data: {id: self.val()}
        }).then(function(result) {
            $('#jobs-catalog-vendor-code').val(result.vendor_code);
            $('#jobs-catalog-vendor-section-name').val(result.section_name);
            $('#jobs-catalog-hour-tariff').val(result.hour_tariff);
            $('#jobs-catalog-hours-required').val(result.hours_required);
            $('#jobs-catalog-price').val(result.price);
        }).catch(function(error) {
            swal('Ошибка', error.message, 'error');
        });
    });
});

JS;

$this->registerJs($script);

