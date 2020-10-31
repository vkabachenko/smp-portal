<?php

/* @var $model app\models\Workshop */
/* @var $form yii\widgets\ActiveForm */

?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'phone1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone1']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone3']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['phone4']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['workshop']['email3']]])
    ->textInput(['maxlength' => true]) ?>

<?php if (\Yii::$app->user->can('admin')): ?>

    <?= $form->field($model, 'mailbox_host')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mailbox_pass')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mailbox_port')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mailbox_encryption')->textInput(['maxlength' => true]) ?>


<?php endif; ?>

