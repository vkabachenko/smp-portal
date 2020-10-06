<?php

use yii\helpers\Html;
use app\models\JobsSection;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BidJob */
/* @var $form yii\widgets\ActiveForm */
/* @var $bid app\models\Bid */
/* @var $jobsCatalog array */
/* @var $jobsCatalogService \app\services\job\JobsCatalogService */
/* @var $action array */
?>

<div>

    <?php $form = ActiveForm::begin([
        'id' => 'bid-job-form',
        'action' => isset($action) ? $action : ''
    ]); ?>

    <div class="form-group">
        <label class="control-label" for="jobs-section-select">Раздел работ</label>
        <?= Html::dropDownList(
                'jobs_section_id',
        '0',
            JobsSection::jobsSectionAsMap($bid->agency_id, true),
            [
                'id' => 'jobs-section-select',
                'class' => 'form-control'
            ]
         ) ?>
    </div>

    <?= $form->field($model, 'jobs_catalog_id')
        ->hiddenInput(['id' => 'jobs-catalog-id'])
    ?>

    <table class="table table-striped table-bordered table-jobs-catalog">

        <thead>
            <tr>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Цена нормочаса</th>
                <th>Нормочасов</th>
                <th>Стоимость</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($jobsCatalog as $jobsKind): ?>

            <tr class="tr-jobs-catalog" data-id="<?= $jobsKind['id'] ?>" data-section="<?= $jobsKind['jobs_section_id'] ?>">
                <td>
                    <?= $jobsKind['vendor_code'] ?>
                </td>
                <td>
                    <?= $jobsKind['name'] ?>
                </td>
                <td>
                    <?= $jobsKind['hour_tariff'] ?>
                </td>
                <td>
                    <?= $jobsKind['hours_required'] ?>
                </td>
                <td>
                    <?= $jobsKind['price'] ?>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-jobs-submit', 'disabled' => true]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {

    $("tbody tr[data-id='" + {$model->jobs_catalog_id} + "']").addClass('active');
    
    
    $('.tr-jobs-catalog').click(function() {
       var self = $(this);
       $('.tr-jobs-catalog').removeClass('active');
       self.addClass('active');
       $('#jobs-catalog-id').val(self.data('id'));
       $('.btn-jobs-submit').attr('disabled', false);
    });
    
    $('#jobs-section-select').change(function() {
        $('.tr-jobs-catalog').show().removeClass('active');
        
        var section = $(this).val();
        if (section === '0') {
            return;
        }
        
        $("tbody tr[data-section!='" + section + "']").hide();
        
    });
});

JS;

$this->registerJs($script);

