<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;

/* @var $this yii\web\View */
/* @var $workshopDataProvider yii\data\ActiveDataProvider */
/* @var $availableWorkshops array */
/* @var $agency \app\models\Agency */

$this->title = 'Мастерские представительства ' . $agency->name;
$this->params['back'] = ['manager/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $workshopDataProvider,
        'rowOptions'=>function (Workshop $workshop) use ($agency)
            {if (!\app\models\AgencyWorkshop::getActive($agency, $workshop)) {return ['class'=>'disabled'];} },
        'columns' => [
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

    <?php if (!empty($availableWorkshops)): ?>
        <?= Html::beginForm(['new-workshop']) ?>

            <div class="form-group">
                <?= Html::label('Добавьте мастерскую', 'available-workshops-id', ['class' => 'control-label']) ?>
                <?= Html::dropDownList('new_workshop', null, $availableWorkshops, [
                    'id' => 'available-workshops-id',
                    'class' => 'form-control'
                ]) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

            </div>

        <?= Html::endForm() ?>
    <?php endif ?>

</div>