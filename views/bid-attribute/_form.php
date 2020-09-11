<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BidAttribute;
use vova07\imperavi\Widget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BidAttribute */
/* @var $form yii\widgets\ActiveForm */

?>

<div>
    <?php $form = ActiveForm::begin(['id' => 'bid-attribute-form']); ?>

    <input type="hidden" name="is_html_description_revert" value="0">

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'attribute')->dropDownList(BidAttribute::getEnabledAttributes()) ?>
    <?php endif ?>

    <?php if ($model->is_html_description): ?>
        <?= $form->field($model, 'description')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 200,
                'imageUpload' => Url::to(['image-upload']),
                'plugins' => [
                    'imagemanager',
                ],
            ],
        ]) ?>
    <?php else: ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'is_html_description')->checkbox(['class' => 'is-html-description']) ?>

    <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_disabled_agencies')->checkbox() ?>

    <?= $form->field($model, 'is_disabled_workshops')->checkbox() ?>

    <?= $form->field($model, 'is_control')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    $('.is-html-description').change(function() {
        $('[name="is_html_description_revert"]').val(1);
        $('#bid-attribute-form').trigger('submit');
    });
JS;

$this->registerJs($script);

