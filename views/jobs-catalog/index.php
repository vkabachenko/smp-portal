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
        <div class="col-xs-6 col-sm-6">
            <?= Html::a('Добавить из шаблона', ['add-excel', 'agencyId' => $agency->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Образец шаблона',
                [
                    'download/default',
                    'filename' => 'template_jobs_sample.xlsx',
                    'path' => \Yii::getAlias('@app/templates/excel/job/sample.xlsx')
                ]);
            ?>
        </div>

    </div>
    <div class="clearfix form-group"></div>


    <div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'jobs_section_id',
                    'header' => 'Раздел работ',
                    'value' => function ($model) {
                        return $model['jobs_section_id'] ? \app\models\JobsSection::findOne($model['jobs_section_id'])->name : null;
                    },
                ],
                [
                    'attribute' => 'vendor_code',
                    'header' => 'Артикул'
                ],
                [
                    'attribute' => 'name',
                    'header' => 'Наименование'
                ],
                [
                    'attribute' => 'hour_tariff',
                    'header' => 'Цена нормочаса'
                ],
                [
                    'attribute' => 'hours_required',
                    'header' => 'Нормочасов'
                ],
                [
                    'attribute' => 'price',
                    'header' => 'Стоимость'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('',
                                ['update', 'id' => $model['id']],
                                ['class' => 'glyphicon glyphicon-pencil']
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('',
                                ['delete', 'id' => $model['id']],
                                ['class' => 'glyphicon glyphicon-trash', 'data' => ['method' => 'post']]
                            );
                        }
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
