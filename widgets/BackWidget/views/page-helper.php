<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\PageHelper */
/* @var $form yii\widgets\ActiveForm */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

?>

<?php if (\Yii::$app->user->can('admin')): ?>

<div>
    <?php $form = ActiveForm::begin(['id' => 'page-helper-form', 'action' => ['page-helper/save']]); ?>

    <?= $form->field($model, 'controller')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'action')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'help_text')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

<?php else: ?>

    <div>
        <?= nl2br(Html::encode($model->help_text)) ?>
    </div>

<?php endif; ?>


