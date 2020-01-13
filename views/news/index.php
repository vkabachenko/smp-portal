<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\News;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row form-group">
        <div class="col-xs-6 col-sm-4">
            <?= Html::a('Создать новость', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-xs-6 col-sm-4">
            <?= Html::a('Разделы', ['news-section/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="clearfix"></div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'created_at',
            'title',
            [
                'attribute' => 'target',
                'value' => function (News $model) {
                    $html = \app\models\News::TARGETS[$model->target];
                    return $html;
                }
            ],
            [
                'attribute' => 'news_section_id',
                'value' => function (News $model) {
                    return $model->news_section_id ? $model->newsSection->name : null;
                }
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function (News $model) {
                    $html = $model->active ? Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) : '';
                    return $html;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{likes}{update}{delete}',
                'buttons' => [
                    'likes' => function ($url, $model, $key) {
                        return Html::a('', ['likes',
                            'id' => $model->id,
                        ],
                            ['class' => 'glyphicon glyphicon-thumbs-up']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
