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
            <?= Html::a('Виды работ',
                ['jobs-catalog/index', 'agencyId' => $model->id],
                ['class' => 'btn btn-success'])
            ?>
        </div>

        <div class="col-xs-6 col-sm-3">

            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Настройка полей заявки
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li>
                        <?= Html::a('Скрыть/показать',
                            ['agency/bid-attributes', 'agencyId' => $model->id],
                            ['class' => 'btn btn-default'])
                        ?>
                    </li>
                    <li>
                        <?= Html::a('Порядок расположения полей',
                            ['agency/bid-attributes-sections', 'agencyId' => $model->id],
                            ['class' => 'btn btn-default'])
                        ?>
                    </li>
                </ul>
            </div>
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


