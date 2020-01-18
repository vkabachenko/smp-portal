<?php


use yii\grid\GridView;
use yii\bootstrap\Html;
use app\models\JobsCatalog;


/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $agency \app\models\Agency */

$this->title = 'Справочник видов работ представительства ' . $agency->name;
$this->params['back'] = \Yii::$app->user->can('admin')
    ? ['agency/update', 'id' => $agency->id]
    : ['manager/index'];
?>

<div>
    <h2><?= Html::encode($this->title) ?></h2>

    <div>

        <div class="col-xs-6 col-sm-3">
            <?= Html::a('Новый вид работы', ['create', 'agencyId' => $agency->id], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-xs-6 col-sm-3">
            <?= Html::a('Разделы работ', ['jobs-section/index', 'agencyId' => $agency->id], ['class' => 'btn btn-primary']) ?>
        </div>

    </div>
    <div class="clearfix form-group"></div>


    <div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'date_actual',
                [
                    'attribute' => 'jobs_section_id',
                    'value' => function (JobsCatalog $model) {
                        return $model->jobs_section_id ? $model->jobsSection->name : null;
                    },
                ],
                'vendor_code',
                'name',
                'hour_tariff',
                'hours_required',
                'price',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                ],
            ],
        ]); ?>
    </div>
</div>
