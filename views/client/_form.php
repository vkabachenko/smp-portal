<?php
/* @var $this yii\web\View */
/* @var $client \app\models\Client */


use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Html;

?>

<style>
    .client-phone-template {
        display: none;
    }
</style>

<div>
    <?php $form = ActiveForm::begin([
        'id' => 'client-form',
        'action' => ['client/save']
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-submit']) ?>
    </div>

    <?= Html::hiddenInput('id', $client->isNewRecord ? '' : $client->id); ?>

    <?= $form->field($client, 'workshop_id')
        ->dropDownList(\app\models\Workshop::workshopsAsMap(), [
            'prompt' => 'Выбор',
            'readonly' => !\Yii::$app->user->can('admin')
            ])
    ?>
    <?= $form->field($client, 'name') ?>
    <?= $form->field($client, 'full_name') ?>
    <?= $form->field($client, 'client_type')->dropDownList(\app\models\Client::CLIENT_TYPES) ?>
    <?= $form->field($client, 'email') ?>
    <?= $form->field($client, 'date_register')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <div class="phones-section">
        <?php if (!$client->isNewRecord): ?>
            <?php foreach ($client->clientPhones as $clientPhone): ?>
                <div class="form-group client-phone">
                    <label>Телефон
                        <?= Html::textInput('clientPhone[]', $clientPhone->phone) ?>
                        <a href="#" class="remove-phone">
                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                        </a>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="form-group client-phone client-phone-template">
            <label>Телефон
                <?= Html::textInput('clientPhone[]') ?>
                <a href="#" class="remove-phone">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </a>
            </label>
        </div>
    </div>

    <div class="form-group">
        <a href="#" class="btn btn-primary add-phone-btn">
            Добавить телефон
        </a>
    </div>

    <?= $form->field($client, 'manager') ?>
    <?= $form->field($client, 'inn') ?>
    <?= $form->field($client, 'kpp') ?>
    <?= $form->field($client, 'address_actual') ?>
    <?= $form->field($client, 'address_legal') ?>
    <?= $form->field($client, 'description')->textarea() ?>
    <?= $form->field($client, 'comment')->textarea() ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(function() {
     $('body').on('click', '.add-phone-btn', function(evt) {
        evt.preventDefault();
        $('.client-phone-template').clone().removeClass('client-phone-template').appendTo('.phones-section');
    });
    
    $('body').on('click', '.remove-phone', function(evt) {
        evt.preventDefault();
        $(this).closest('label').closest('div').remove();
    }); 
})
JS;


$this->registerJs($script);

