<?php

use yii\bootstrap\ActiveForm;
use app\models\Bid;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $workshop app\models\Workshop */
/* @var $exchangeForm  \app\models\form\Exchange1CForm */

$this->title = 'Настройка обмена с 1C';
$this->params['back'] = ['workshop/update', 'id' => $workshop->id];

?>

<h2><?= Html::encode($this->title) ?></h2>

<p>
    Введите названия полей 1С, соответствующие полям на портале
</p>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

<?php foreach ($exchangeForm->attributes as $attribute => $value): ?>

    <?= $form->field($exchangeForm, 'attributes[' . $attribute. ']')
        ->label(Bid::EXCHANGE_1C_ATTRIBUTES[$attribute]); ?>

<?php endforeach; ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>
