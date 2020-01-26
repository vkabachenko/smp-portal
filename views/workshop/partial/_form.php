<?php

/* @var $model app\models\Workshop */
/* @var $form yii\widgets\ActiveForm */

?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'phone1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone1']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone3']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone4',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['phone4']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email1',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email1']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email2',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email2']]])
    ->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email3',
    ['labelOptions' => ['class' => 'column-hint', 'data-title' => \Yii::$app->params['fieldTitle']['agency']['email3']]])
    ->textInput(['maxlength' => true]) ?>

