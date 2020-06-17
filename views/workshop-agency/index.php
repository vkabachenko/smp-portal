<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\AgencyWorkshop;

/* @var $this yii\web\View */
/* @var $agencyDataProvider yii\data\ActiveDataProvider */
/* @var $workshop \app\models\Workshop */

$this->title = 'Представительства мастерской  ' . $workshop->name;
$this->params['back'] = ['master/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $agencyDataProvider,
        'rowOptions'=>function (\app\models\Agency $agency) use ($workshop)
            {if (!\app\models\AgencyWorkshop::getActive($agency, $workshop)) {return ['class'=>'disabled enabled-events'];} },
        'columns' => [
            'name',
            [
                'attribute' => 'manufacturer_id',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Agency */
                    $html = $model->manufacturer->name;
                    return $html;
                },
            ],
            [
                'header' => 'Договор с агентством',
                'format' => 'raw',
                'value' => function ($model)  use ($workshop) {
                    $html = '';
                    $agencyWorkshop = AgencyWorkshop::find()->where(
                            [
                                'agency_id' => $model->id,
                                'workshop_id' => $workshop->id
                            ])->one();
                    $html .= $agencyWorkshop->contract_nom
                        ? '<span>' . '№ ' . $agencyWorkshop->contract_nom . '</span>'
                        : '';
                    $html .= $agencyWorkshop->contract_date
                        ? '<span>' . ' от ' . \Yii::$app->formatter->asDate($agencyWorkshop->contract_date) . '</span>'
                        : '';

                    /* @var $officialDoc \app\models\OfficialDocs */
                    $officialDoc = AgencyWorkshop::getOfficialDoc($model, $workshop);
                    if (!is_null($officialDoc)) {
                        $html .= ' ' . Html::a($officialDoc->file_name,
                            ['download/default', 'path' => $officialDoc->getPath(), 'filename' => $officialDoc->file_name]
                        );
                    }
                    return $html;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{toggle}',
                'buttons' => [
                    'update' => function ($url, $model, $key) use ($workshop) {
                        return Html::a('', ['update',
                            'agencyId' => $model->id,
                            'workshopId' => $workshop->id
                        ],
                            ['class' => 'glyphicon glyphicon-pencil']);
                    },
                    'toggle' => function ($url, $model, $key) use ($workshop) {
                        return Html::a('', ['toggle-active',
                            'agencyId' => $model->id,
                            'workshopId' => $workshop->id,
                            'returnUrl' => Url::to(['workshop-agency/agencies', 'workshopId' => $workshop->id])
                        ],
                            ['class' => 'glyphicon glyphicon-repeat']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>