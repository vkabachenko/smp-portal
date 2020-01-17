<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!$model->isNewRecord): ?>

    <div class="form-group">

        <div class="col-xs-6 col-sm-3">
            <?= Html::a('Справочник работ',
                ['jobs-catalog/index', 'agencyId' => $model->id],
                ['class' => 'btn btn-success'])
            ?>
        </div>

        <div class="col-xs-6 col-sm-3">
                <?= Html::a('Поля заявки',
                    ['agency/bid-attributes', 'agencyId' => $model->id],
                    ['class' => 'btn btn-primary'])
                ?>
        </div>

    </div>

    <div class="clearfix form-group"></div>

<?php endif; ?>

<div class="agency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('partial/_form', compact('form','model')) ?>

    <div>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>


