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
/* @var $action array */
?>

<div>

    <?php $form = ActiveForm::begin([
            'id' => 'bid-job-form',
            'action' => isset($action) ? $action : ''
    ]); ?>

    <?= $form->field($jobsCatalog, 'jobs_section_id')
        ->dropDownList(\app\models\JobsSection::jobsSectionAsMap($bid->agency_id), [
            'id' => 'jobs-section-select',
            'data-url' => Url::to(['bid-job/change-jobs-section'])
        ])
    ?>

    <?= $form->field($model, 'jobs_catalog_id')
        ->dropDownList($jobsCatalogService->jobsCatalogAsMap($jobsCatalog->jobs_section_id), [
            'id' => 'jobs-catalog-select',
            'data-url' => Url::to(['bid-job/change-jobs-catalog'])
        ])
    ?>

    <?= $form->field($jobsCatalog, 'vendor_code')->textInput([
            'disabled' => true,
            'id' => 'jobs-catalog-vendor-code'
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
            $('#jobs-catalog-hour-tariff').val(result.hour_tariff);
            $('#jobs-catalog-hours-required').val(result.hours_required);
            $('#jobs-catalog-price').val(result.price);
        }).catch(function(error) {
            swal('Ошибка', error.message, 'error');
        });
    });
    
    $('#jobs-section-select').change(function() {
        var agencyId = {$bid->agency_id};
        var self = $(this);
        $.ajax({
            url: self.data('url'),
            method: 'POST',
            data: {id: self.val(), agencyId: agencyId}
        }).then(function(result) {
            var catalog = $('#jobs-catalog-select')[0];
            catalog.options.length = 0;
            for (var id in result) {
                catalog.options[catalog.options.length] = new Option(result[id], id, false, false);
            }
        }).catch(function(error) {
            swal('Ошибка', error.message, 'error');
        });
    });
});

JS;

$this->registerJs($script);

