<?php

use vkabachenko\filepond\widget\FilepondWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\QuaggaAsset;
use app\helpers\bid\EditHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
/* @var $hints array */

QuaggaAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin(); ?>
    <?php
    $attributes = EditHelper::getAttributesEdit($model, \Yii::$app->user, $form, $hints);
    $section1 = EditHelper::getEditSection($model, \Yii::$app->user, 'section1');
    $section2 = EditHelper::getEditSection($model, \Yii::$app->user, 'section2', false);
    $section3 = EditHelper::getEditSection($model, \Yii::$app->user, 'section3', false);
    $section4 = EditHelper::getEditSection($model, \Yii::$app->user, 'section4', false);
    $section5 = EditHelper::getEditSection($model, \Yii::$app->user, 'section5', false);


    $items = [];

    if (!empty($section1)) {
        $items[] =             [
            'label' => 'Раздел 1',
            'content' => $this->render('_edit-section-table', ['attributes' => $attributes, 'section' => $section1])
        ];
    }

    if (!empty($section2)) {
        $items[] =             [
            'label' => 'Раздел 2',
            'content' => $this->render('_edit-section-table', ['attributes' => $attributes, 'section' => $section2])
        ];
    }

    if (!empty($section3)) {
        $items[] =             [
            'label' => 'Раздел 3',
            'content' => $this->render('_edit-section-table', ['attributes' => $attributes, 'section' => $section3])
        ];
    }

    if (!empty($section4)) {
        $items[] =             [
            'label' => 'Раздел 4',
            'content' => $this->render('_edit-section-table', ['attributes' => $attributes, 'section' => $section4])
        ];
    }

    if (!empty($section5)) {
        $items[] =             [
            'label' => 'Раздел 5',
            'content' => $this->render('_edit-section-table', ['attributes' => $attributes, 'section' => $section5])
        ];
    }

    ?>

    <?= \yii\bootstrap\Tabs::widget(
        [
            'linkOptions' => ['class' => 'btn btn-primary'],
            'itemOptions' => ['style' => 'margin-top: 10px;'],
            'items' => $items
        ]
    ) ?>

        <div class="form-group">
            <?= Html::label('Загрузить фотографии', null, ['class' => 'control-label']) ?>
            <?= FilepondWidget::widget([
                'filepondClass' => 'load-bid-images',
                'model' => $uploadForm,
                'attribute' => 'files[]',
                'multiple' => true,
                'instanceOptions' => [
                    'allowFileTypeValidation' => true,
                    'acceptedFileTypes' => ['image/*']
                ]
            ]);
            ?>
        </div>

    <div class="form-group">
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Сохранить', ['name' => 'save', 'class' => 'btn btn-success', 'value' => '1']) ?>
        </div>

        <?php if (\Yii::$app->user->can('sendAct', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3 col-xs-6">
                <?= Html::submitButton('Отправить', ['name' => 'send', 'class' => 'btn btn-primary', 'value' => '1']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('manageJobs', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3 col-xs-6">
                <?= Html::submitButton('Работы', ['name' => 'job', 'class' => 'btn btn-primary', 'value' => '1']) ?>
            </div>
        <?php endif; ?>

        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::submitButton('Запчасти', ['name' => 'spare', 'class' => 'btn btn-primary', 'value' => '1']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


    <?= $this->render('modal/client', ['client' => $model->client_id
        ? $model->client
        : new \app\models\Client([
            'workshop_id' => \Yii::$app->user->identity->master ? \Yii::$app->user->identity->master->workshop_id : null
        ])])
    ?>

<?php
$script = <<<JS
    $(function(){
            var App = {
            init: function() {
                App.attachListeners();
            },
            config: {
                reader: "code_128",
                length: 10
            },
            attachListeners: function() {
                $("#bid-serial-number-file").on("change", function(e) {
                    if (e.target.files && e.target.files.length) {
                        App.decode(URL.createObjectURL(e.target.files[0]));
                    }
                });
            },
            detachListeners: function() {
                $("#bid-serial-number-file").off("change");
            },
            decode: function(src) {
                var self = this,
                    config = $.extend({}, self.state, {src: src});
    
                Quagga.decodeSingle(config, function(result) {});
            },
            state: {
                inputStream: {
                    size: 800
                },
                locator: {
                    patchSize: "medium",
                    halfSample: false
                },
                numOfWorkers: 1,
                decoder: {
                    readers: [{
                        format: "code_128_reader",
                        config: {}
                    }]
                },
                locate: true,
                src: null
            }
        };
    
        App.init();
    
        Quagga.onProcessed(function(result) {
            console.log('processed');
            console.log(result);
        });
    
        Quagga.onDetected(function(result) {
            console.log('detected');
            console.log(result);
            var code = result.codeResult.code;
            if (code) {
                $('#bid-serial-number').val(code);
            }
        });
        
        
        $('#bid-manufacturer-id').change(function(){
              $('#bid-brand-id').val('');
              $('#bid-brand-name').val('');
              $('#bid-brand-id').trigger('change');
        });
        
        $('#bid-brand-id').change(function(){
            if ($('#bid-brand-model-id').val()) {
              $('#bid-brand-model-id').val('');
              $('#bid-brand-model-name').val('');
            }
            if ($('#bid-composition-id').val()) {
              $('#bid-composition-id').val('');
              $('#bid-composition-name').val('');
            }
        });
    });
JS;

$this->registerJs($script);
