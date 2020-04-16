<?php

use yii\bootstrap\ActiveForm;
use app\models\Bid;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $workshop app\models\Workshop */
/* @var $exchangeForm  \app\models\form\Exchange1CForm */

$this->title = 'Настройка обмена с 1C';
$this->params['back'] = ['workshop/update', 'id' => $workshop->id];

?>

<h2><?= Html::encode($this->title) ?></h2>



<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <div>
        <div class="col-md-9 col-sm-6 col-xs-12" style="padding-left: 0;">
            <?= $form->field($workshop, 'token')->textInput(['maxlength' => true, 'id' => 'workshop-token']) ?>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <button id="workshop-token-btn" class="btn btn-success">
                Сгенерировать
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Url для импорта/экспорта (только при наличии токена)</h3>
        </div>
        <div class="panel-body">
            <?php if ($workshop->token): ?>
                <div>
                    <?= Url::to(['exchange/import', 'token' => $workshop->token], true) ?>
                </div
                <div>
                    <?= Url::to(['exchange/export', 'token' => $workshop->token], true) ?>
                </div
            <?php endif; ?>
        </div>
    </div>


    <h3>
        Введите названия полей 1С, соответствующие полям на портале
    </h3>

<?php foreach ($exchangeForm->attributes as $attribute => $value): ?>

    <?= $form->field($exchangeForm, 'attributes[' . $attribute. ']')
        ->label(Bid::EXCHANGE_1C_ATTRIBUTES[$attribute]); ?>

<?php endforeach; ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>

<?php
$script = <<<JS
        $(function(){
           $('#workshop-token-btn').click(function(evt) {
               evt.preventDefault();
               var token = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                $('#workshop-token').val(token); 
           }); 
        });
JS;
$this->registerJs($script);