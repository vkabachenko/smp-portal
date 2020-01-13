<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\News;
use app\models\NewsSection;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
        ],
    ]) ?>

    <?= $form->field($model, 'target')->dropDownList(News::TARGETS) ?>
    <?= $form->field($model, 'news_section_id')->dropDownList(NewsSection::newsSectionAsMap(),
        ['prompt' => 'Выбор'])
    ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'active')->checkbox() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
