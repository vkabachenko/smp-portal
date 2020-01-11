<?php

use yii\bootstrap\Html;
use app\models\News;

/* @var $model \app\models\News */
?>
<div class="news-article-all">
    <p class="news-title">
        <?= Html::a($model->title, ['news-show/article', 'id' => $model->id]) ?>
    </p>
    <p class="news-date">
        <?= News::TARGETS[$model->target] . ' ' . $model->updated_at ?>
    </p>
</div>
