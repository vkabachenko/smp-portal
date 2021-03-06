<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Workshop;
use yii\helpers\Url;
use app\models\AgencyWorkshop;

/* @var $this yii\web\View */
/* @var $workshopDataProvider yii\data\ActiveDataProvider */
/* @var $availableWorkshops array */
/* @var $agency \app\models\Agency */

$this->title = 'Мастерские представительства ' . $agency->name;
$this->params['back'] = \Yii::$app->user->can('admin') ? ['agency/index'] : ['manager/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $workshopDataProvider,
        'rowOptions'=>function (Workshop $workshop) use ($agency)
            {if (!\app\models\AgencyWorkshop::getActive($agency, $workshop)) {return ['class'=>'disabled enabled-events'];} },
        'columns' => [
            'name',
            [
                'header' => 'Договор с агентством',
                'format' => 'raw',
                'value' => function ($model)  use ($agency) {
                    $html = '';
                    $agencyWorkshop = AgencyWorkshop::find()->where(
                        [
                            'agency_id' => $agency->id,
                            'workshop_id' => $model->id
                        ])->one();
                    $html .= $agencyWorkshop->contract_nom
                        ? '<span>' . '№ ' . $agencyWorkshop->contract_nom . '</span>'
                        : '';
                    $html .= $agencyWorkshop->contract_date
                        ? '<span>' . ' от ' . \Yii::$app->formatter->asDate($agencyWorkshop->contract_date) . '</span>'
                        : '';

                    /* @var $officialDoc \app\models\OfficialDocs */
                    $officialDoc = AgencyWorkshop::getOfficialDoc($agency, $model);
                    if (!is_null($officialDoc)) {
                        $html .= ' ' . Html::a($officialDoc->file_name,
                                ['download/default', 'path' => $officialDoc->getPath(), 'filename' => $officialDoc->file_name]
                            );
                    }
                    return $html;
                }
            ],
            \Yii::$app->user->can('admin')
                ?
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}{toggle}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) use ($agency) {
                            return Html::a('', $url. '&agencyId=' . $agency->id,
                                ['class' => 'glyphicon glyphicon-trash', 'data' => ['method' => 'post']]);
                        },
                        'toggle' => function ($url, $model, $key) use ($agency) {
                            return Html::a('', ['workshop-agency/toggle-active',
                                'agencyId' => $agency->id,
                                'workshopId' => $model->id,
                                'returnUrl' => Url::to(['agency-workshop/workshops', 'agencyId' => $agency->id])
                            ],
                                ['class' => 'glyphicon glyphicon-repeat']);
                        },
                    ],
                ]
                :
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                ],
        ],
    ]); ?>

    <?php if (!empty($availableWorkshops)): ?>
        <?= Html::beginForm(['new-workshop','agencyId' => $agency->id]) ?>

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